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
    public static function getCodes()
    {
        $items = [];
        $types = self::all();
        foreach ($types as $code=>$type) {
            array_push($items, $code);
        }

        return $items;
    }

    public static function options($only_vaild = false)
    {
        $items = $only_vaild ? [] : ['' => trans('php-core::system.null')];

        $types = self::all();
        foreach ($types as $key=>$value) {
            $items = array_merge($items, [$key => trans('php-morph-board::system.boardType.'.$key)]);
        }

        return $items;
    }

    public static function all()
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
