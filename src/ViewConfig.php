<?php
/**
 * Aura\View Provider for Aura\Di
 *
 * PHP version 5
 *
 * Copyright (C) 2016 Jake Johns
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 *
 * @category  Config
 * @package   Fusible\ViewProvider
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2016 Jake Johns
 * @license   http://jnj.mit-license.org/2016 MIT License
 * @link      https://github.com/fusible/fusible.view-provider
 */

namespace Fusible\ViewProvider;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

use Aura\Html;
use Aura\View;

/**
 * Config
 *
 * @category Config
 * @package  Fusible\ViewProvider
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/2016 MIT License
 * @link     https://github.com/fusible/fusible.view-provider
 *
 * @see ContainerConfig
 */
class ViewConfig extends ContainerConfig
{
    protected $values = [
        ViewMap::class     => [],
        ViewPaths::class   => [],
        LayoutMap::class   => [],
        LayoutPaths::class => []
    ];

    /**
     * SetTemplatePath
     *
     * @param mixed $path DESCRIPTION
     *
     * @return mixed
     *
     * @access public
     */
    public function addTemplatePath($path)
    {
        $this->values[ViewPaths::class][] = $path . '/views';
        $this->values[LayoutPaths::class][] = $path . '/layouts';
    }

    /**
     * Define Aura\View and Aura\Html factories and services
     *
     * @param Container $di DI Container
     *
     * @return void
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function define(Container $di)
    {
        foreach ($this->values as $key => $value) {
            $di->values[$key] = array_merge(
                $value, $di->values[$key] ?? []
            );
        }

        $di->set(
            View\ViewFactory::class,
            $di->lazyNew(View\ViewFactory::class)
        );

        $di->set(
            View\View::class,
            $di->lazyGetCall(
                View\ViewFactory::class,
                'newInstance',
                $di->lazyGet(Html\HelperLocator::class),
                $di->lazyValue(ViewMap::class),
                $di->lazyValue(ViewPaths::class),
                $di->lazyValue(LayoutMap::class),
                $di->lazyValue(LayoutPaths::class)
            )
        );
    }
}
