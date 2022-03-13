<?php

namespace WalkerChiu\MorphBoard\Models\Constants;

/**
 * @license MIT
 * @package WalkerChiu\MorphBoard
 *
 *
 */

class BoardType
{
    /**
     * @return Array
     */
    public static function getCodes(): array
    {
        $items = [];
        $types = self::all();
        foreach ($types as $code => $type) {
            array_push($items, $code);
        }

        return $items;
    }

    /**
     * @param Bool  $onlyVaild
     * @return Array
     */
    public static function options($onlyVaild = false): array
    {
        $items = $onlyVaild ? [] : ['' => trans('php-core::system.null')];

        $types = self::all();
        foreach ($types as $key => $value) {
            $items = array_merge($items, [$key => trans('php-morph-board::system.boardType.'.$key)]);
        }

        return $items;
    }

    /**
     * @return Array
     */
    public static function all(): array
    {
        return [
            'about'   => 'About',
            'ad'      => 'Advertisement',
            'article' => 'Article',
            'brands'  => 'Brands',
            'contact' => 'Contact',
            'cover'   => 'Cover',
            'header'  => 'Header',
            'help'    => 'Help',
            'faq'     => 'FAQ',
            'footer'  => 'Footer',
            'privacy' => 'Privacy Policy',
            'rule'    => 'Terms of Use',
            'service' => 'Service',
            'side'    => 'Side',
            'story'   => 'Story',
            'news'    => 'News'
        ];
    }
}
