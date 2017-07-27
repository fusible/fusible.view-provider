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
use Aura\Di\ConfigCollection;

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
class Config extends ConfigCollection
{
    protected $configs = [];

    /**
     * __construct
     *
     * @param mixed $templates DESCRIPTION
     * @param array $helpers   DESCRIPTION
     *
     * @return mixed
     *
     * @access public
     */
    public function __construct($templates = null, array $helpers = [])
    {
        $this->configs = [
            ViewConfig::class => new ViewConfig,
            ViewHelperConfig::class => new ViewHelperConfig
        ];

        if ($templates) {
            $this->view()->addTemplatePath($templates);
        }

        if ($helpers) {
            $this->helper()->addHelpers($helpers);
        }
    }

    /**
     * View
     *
     * @return mixed
     *
     * @access public
     */
    public function view()
    {
        return $this->configs[ViewConfig::class];
    }

    /**
     * Helper
     *
     * @return mixed
     * @throws exceptionclass [description]
     *
     * @access public
     */
    public function helper()
    {
        return $this->configs[ViewHelperConfig::class];
    }
}
