<?php
// @codingStandardsIgnoreFile

namespace Fusible\ViewProvider;

use Aura\Di\AbstractContainerConfigTest;

class ConfigTest extends AbstractContainerConfigTest
{

    protected function getConfigClasses()
    {
        return [
            'Fusible\ViewProvider\Config'
        ];
    }

    public function provideGet()
    {
        return [
            ['aura/view:factory', 'Aura\View\ViewFactory'],
            ['aura/html:factory', 'Aura\Html\HelperLocatorFactory'],
            ['aura/view:view', 'Aura\View\View'],
            ['aura/html:helpers', 'Aura\Html\HelperLocator'],
            ['aura/html:escaper', 'Aura\Html\Escaper'],
            ['aura/html:escaper_factory', 'Aura\Html\EscaperFactory']
        ];
    }

    public function provideNewInstance()
    {
        return [
            ['Aura\View\ViewFactory'],
            ['Aura\Html\HelperLocatorFactory'],
        ];
    }

    public function testConfig()
    {
        $this->assertInstanceOf(
            'Aura\Html\HelperLocator',
            $this->di->get('aura/view:view')->getHelpers()
        );
    }
}
