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
            'aura/html:factory',
            $di->lazyNew('Aura\Html\HelperLocatorFactory')
        );

        $di->set(
            'aura/html:helpers',
            $di->lazyGetCall(
                'aura/html:factory',
                'newInstance'
            )
        );


        // Aura\View
        $di->set(
            'aura/view:factory',
            $di->lazyNew('Aura\View\ViewFactory')
        );

        $di->set(
            'aura/view:view',
            $di->lazyGetCall(
                'aura/view:factory',
                'newInstance',
                $di->lazyGet('aura/html:helpers')
            )
        );
    }
}
