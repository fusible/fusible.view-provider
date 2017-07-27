<?php
// @codingStandardsIgnoreFile

namespace Fusible\ViewProvider;

use Aura\Di\AbstractContainerConfigTest;

use Aura\View;
use Aura\Html;

class ConfigTest extends AbstractContainerConfigTest
{

    protected function getConfigClasses()
    {
        return [Config::class];
    }

    public function provideGet()
    {
        return [
            [View\ViewFactory::class, View\ViewFactory::class],
            [Html\HelperLocatorFactory::class, Html\HelperLocatorFactory::class],
            [View\View::class, View\View::class],
            [Html\HelperLocator::class, Html\HelperLocator::class],
            [Html\Escaper::class, Html\Escaper::class],
            [Html\EscaperFactory::class, Html\EscaperFactory::class]
        ];
    }

    public function provideNewInstance()
    {
        return [
            [View\ViewFactory::class],
            [Html\HelperLocatorFactory::class],
        ];
    }

    public function testConfig()
    {
        $this->assertInstanceOf(
            Html\HelperLocator::class,
            $this->di->get(View\View::class)->getHelpers()
        );
    }
}
