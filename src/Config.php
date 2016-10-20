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

use Aura\Html\HelperLocatorFactory;
use Aura\Html\EscaperFactory;
use Aura\Html\Helper\AbstractHelper;
use Aura\View\ViewFactory;

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
class Config extends ContainerConfig
{
    const VIEW_FACTORY = 'aura/view:factory';
    const VIEW         = 'aura/view:view';
    const HTML_FACTORY = 'aura/html:factory';
    const HTML_HELPERS = 'aura/html:helpers';
    const HTML_ESCAPER = 'aura/html:escaper';
    const HTML_ESCAPER_FACTORY = 'aura/html:escaper_factory';

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
        // Aura\Html
        $di->set(
            static::HTML_FACTORY,
            $di->lazyNew(HelperLocatorFactory::class)
        );

        $di->set(
            static::HTML_HELPERS,
            $di->lazyGetCall(
                static::HTML_FACTORY,
                'newInstance'
            )
        );

        $di->set(
            static::HTML_ESCAPER_FACTORY,
            $di->lazyNew(EscaperFactory::class)
        );

        $di->set(
            static::HTML_ESCAPER,
            $di->lazyGetCall(static::HTML_ESCAPER_FACTORY, 'newInstance')
        );

        $di->params[AbstractHelper::class] = [
            'escaper' => $di->lazyGet(static::HTML_ESCAPER)
        ];


        // Aura\View
        $di->set(
            static::VIEW_FACTORY,
            $di->lazyNew(ViewFactory::class)
        );

        $di->set(
            static::VIEW,
            $di->lazyGetCall(
                static::VIEW_FACTORY,
                'newInstance',
                $di->lazyGet(static::HTML_HELPERS)
            )
        );

    }
}
