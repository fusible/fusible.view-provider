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

/**
 * View Helper Config
 *
 * @category Config
 * @package  Fusible\ViewProvider
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/2016 MIT License
 * @link     https://github.com/fusible/fusible.view-provider
 *
 * @see ContainerConfig
 */
class ViewHelperConfig extends ContainerConfig
{

    protected $specs = [];

    /**
     * __construct
     *
     * @param array $specs helper specs
     *
     * @return mixed
     *
     * @access public
     */
    public function __construct(array $specs = [])
    {
        $this->specs = $specs;
    }

    /**
     * AddHelpers
     *
     * @param array $specs DESCRIPTION
     *
     * @return mixed
     * @throws exceptionclass [description]
     *
     * @access public
     */
    public function addHelpers(array $specs)
    {
        $this->specs = array_merge($this->specs, $specs);
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
        $di->set(
            Html\HelperLocatorFactory::class,
            $di->lazyNew(Html\HelperLocatorFactory::class)
        );

        $di->set(
            Html\HelperLocator::class,
            $di->lazyGetCall(
                Html\HelperLocatorFactory::class,
                'newInstance'
            )
        );

        $di->set(
            Html\EscaperFactory::class,
            $di->lazyNew(Html\EscaperFactory::class)
        );

        $di->set(
            Html\Escaper::class,
            $di->lazyGetCall(Html\EscaperFactory::class, 'newInstance')
        );

        $di->params[Html\Helper\AbstractHelper::class] = [
            'escaper' => $di->lazyGet(Html\Escaper::class)
        ];
    }

    /**
     * Modify
     *
     * @param Container $di DESCRIPTION
     *
     * @return null
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function modify(Container $di)
    {
        $helpers  = $di->get(Html\HelperLocator::class);
        $resolver = $di->newResolutionHelper();
        foreach ($this->getFactories($resolver) as $name => $factory) {
            $helpers->set($name, $factory);
        }
    }

    /**
     * GetFactories
     *
     * @param callable $resolve DESCRIPTION
     *
     * @return mixed
     *
     * @access protected
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected function getFactories(callable $resolve)
    {
        $factories = [];
        foreach ($this->specs as $name => $class) {
            $factories[$name] = function () use ($resolve, $class) {
                return $resolve($class);
            };
        }
        return $factories;
    }

}
