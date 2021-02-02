<?php

namespace WalkerChiu\MorphBoard\Models\Repositories;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Forms\FormTrait;
use WalkerChiu\Core\Models\Repositories\Repository;
use WalkerChiu\Core\Models\Repositories\RepositoryHasHostTrait;

class BoardRepository extends Repository
{
    use FormTrait;
    use RepositoryHasHostTrait;

    protected $entity;

    public function __construct()
    {
        $this->entity = App::make(config('wk-core.class.morph-board.board'));
    }

    /**
     * @param String $host_type
     * @param String $host_id
     * @param String $code
     * @param Array  $data
     * @param Int    $page
     * @param Int    $nums per page
     * @return Array
     */
    public function list($host_type, $host_id, String $code, Array $data, $page = null, $nums = null, $is_enabled = null)
    {
        $this->assertForPagination($page, $nums);

        if (empty($host_type) || empty($host_id)) {
            $entity = $this->entity;
        } else {
            $entity = $this->baseQueryForRepository($host_type, $host_id, $target, $target_is_enabled);
        }
        if ($is_enabled === true)      $entity = $entity->ofEnabled();
        elseif ($is_enabled === false) $entity = $entity->ofDisabled();

        $data = array_map('trim', $data);
        $records = $entity->with(['langs' => function ($query) use ($code) {
                                $query->ofCurrent()
                                      ->ofCode($code);
                            }])
                          ->when( config('wk-morph-board.onoff.morph-tag') && !empty(config('wk-core.class.morph-tag.tag')), function ($query) {
                                return $query->with(['tags', 'tags.langs']);
                            })
                          ->when($data, function ($query, $data) {
                              return $query->unless(empty($data['id']), function ($query) use ($data) {
                                          return $query->where('id', $data['id']);
                                      })
                                      ->unless(empty($data['type']), function ($query) use ($data) {
                                          return $query->where('type', $data['type']);
                                      })
                                      ->unless(empty($data['user_id']), function ($query) use ($data) {
                                          return $query->where('user_id', $data['user_id']);
                                      })
                                      ->unless(empty($data['serial']), function ($query) use ($data) {
                                          return $query->where('serial', $data['serial']);
                                      })
                                      ->unless(empty($data['identifier']), function ($query) use ($data) {
                                          return $query->where('identifier', $data['identifier']);
                                      })
                                      ->when(isset($data['is_highlighted']), function ($query) use ($data) {
                                          return $query->where('is_highlighted', $data['is_highlighted']);
                                      })
                                      ->unless(empty($data['name']), function ($query) use ($data) {
                                          return $query->whereHas('langs', function($query) use ($data) {
                                              $query->ofCurrent()
                                                    ->where('key', 'name')
                                                    ->where('value', $data['name']);
                                          });
                                      })
                                      ->unless(empty($data['description']), function ($query) use ($data) {
                                          return $query->whereHas('langs', function($query) use ($data) {
                                              $query->ofCurrent()
                                                    ->where('key', 'description')
                                                    ->where('value', $data['description']);
                                          });
                                      })
                                      ->unless(empty($data['keywords']), function ($query) use ($data) {
                                          return $query->whereHas('langs', function($query) use ($data) {
                                              $query->ofCurrent()
                                                    ->where('key', 'keywords')
                                                    ->where('value', 'LIKE', "%".$data['keywords']."%");
                                          });
                                      })
                                      ->unless(empty($data['remarks']), function ($query) use ($data) {
                                          return $query->whereHas('langs', function($query) use ($data) {
                                              $query->ofCurrent()
                                                    ->where('key', 'remarks')
                                                    ->where('value', 'LIKE', "%".$data['remarks']."%");
                                          });
                                      })
                                      ->unless(empty($data['content']), function ($query) use ($data) {
                                          return $query->whereHas('langs', function($query) use ($data) {
                                              $query->ofCurrent()
                                                    ->where('key', 'content')
                                                    ->where('value', $data['content']);
                                          });
                                      });
                            })
                          ->orderBy('updated_at', 'DESC')
                          ->get()
                          ->when(is_integer($page) && is_integer($nums), function ($query) use ($page, $nums) {
                                return $query->forPage($page, $nums);
                            });
        $list = [];
        foreach ($records as $record) {
            $this->setEntity($record);

            $data = $record->toArray();
            array_push($list,
                array_merge($data, [
                    'name'        => $record->findLangByKey('name'),
                    'description' => $record->findLangByKey('description'),
                    'keywords'    => $record->findLangByKey('keywords'),
                    'remarks'     => $record->findLangByKey('remarks'),
                    'content'     => $record->findLangByKey('content')
                ])
            );
        }

        return $list;
    }

    /**
     * @param Board $entity
     * @param Array|String $code
     * @return Array
     */
    public function show($entity, $code)
    {
    }
}
