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
class Config extends ContainerConfig
{
    // View
    const VIEW         = View\View::class;
    const VIEW_FACTORY = View\ViewFactory::class;

    // View params
    const VIEW_MAP     = self::class . '::VIEW_MAP';
    const VIEW_PATHS   = self::class . '::VIEW_PATHS';
    const LAYOUT_MAP   = self::class . '::LAYOUT_MAP';
    const LAYOUT_PATHS = self::class . '::LAYOUT_PATHS';

    // Helper
    const HELPER_LOCATOR = Html\HelperLocator::class;
    const HELPER_FACTORY = Html\HelperLocatorFactory::class;

    // Helpers
    const HELPER_SPECS   = self::class . '::HELPER_SPECS';

    // Escaper
    const ESCAPER_FACTORY = Html\EscaperFactory::class;
    const ESCAPER         = Html\Escaper::class;


    protected $params = [
        self::VIEW_MAP      => [],
        self::VIEW_PATHS    => [],
        self::LAYOUT_MAP    => [],
        self::LAYOUT_PATHS  => [],
        self::HELPER_SPECS  => []
    ];

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
        foreach ($this->params as $key => $value) {
            if (! isset($di->values[$key])) {
                $di->values[$key] = $value;
            }
        }

        $this->defineView($di);
        $this->defineHelpers($di);
    }

    /**
     * Define Aura\Html factories and services
     *
     * @param Container $di DI Container
     *
     * @return void
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected function defineHelpers(Container $di)
    {
        $di->set(
            self::HELPER_FACTORY,
            $di->lazyNew(Html\HelperLocatorFactory::class)
        );

        $di->set(
            self::HELPER_LOCATOR,
            $di->lazyGetCall(self::HELPER_FACTORY, 'newInstance')
        );

        $di->set(
            self::ESCAPER_FACTORY,
            $di->lazyNew(Html\EscaperFactory::class)
        );

        $di->set(
            self::ESCAPER,
            $di->lazyGetCall(self::ESCAPER_FACTORY, 'newInstance')
        );

        $di->params[Html\Helper\AbstractHelper::class] = [
            'escaper' => $di->lazyGet(self::ESCAPER)
        ];
    }

    /**
     * Define Aura\View factories and services
     *
     * @param Container $di DI Container
     *
     * @return void
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected function defineView(Container $di)
    {
        $di->set(
            self::VIEW_FACTORY,
            $di->lazyNew(View\ViewFactory::class)
        );

        $di->set(
            self::VIEW,
            $di->lazyGetCall(
                View\ViewFactory::class,
                'newInstance',
                $di->lazyGet(self::HELPER_LOCATOR),
                $di->lazyValue(self::VIEW_MAP),
                $di->lazyValue(self::VIEW_PATHS),
                $di->lazyValue(self::LAYOUT_MAP),
                $di->lazyValue(self::LAYOUT_PATHS)
            )
        );
    }

    /**
     * Define Add helpers
     *
     * @param Container $di DI Container
     *
     * @return void
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function modify(Container $di)
    {
        $specs = $di->lazyValue(self::HELPER_SPECS);
        $specs = $specs();
        if (! $specs) {
            return;
        }

        $helpers = $di->get(self::HELPER_LOCATOR);
        $resolve = $di->newResolutionHelper();

        foreach ($specs as $key => $spec) {

            $factory = function () use ($resolve, $spec) {
                return $resolve($spec);
            };

            $helpers->set($key, $factory);
        }
    }

}
