<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Appform\BackendBundle\Entity\Settings\WebSiteSetting;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Settings
 */
class Settings
{
    /**
     * @var ArrayCollection
     */
    private $settings;

    /**
     * @param ContainerInterface $container
     */
    public function __construct($container = null)
    {
        if (!empty($container)) {
            $this->settings = new ArrayCollection($container->get('doctrine')->getManager()->getRepository('AppformBackendBundle:Setting')->findAll());
        }
        if (empty($this->settings)) {
            $this->settings = new ArrayCollection();
        }
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param $name
     * @return object|null
     */
    private function getSetting($name)
    {
        foreach ($this->settings as $setting) {
            if ($setting->getName() == $name) {
                return $setting->getValue();
            }
        }
        return null;
    }

    /**
     * @param $name
     * @param $value
     * @return Settings
     */
    private function setSetting($name, $value)
    {
        foreach ($this->settings as $setting) {
            if ($setting->getName() == $name) {
                $setting->setValue($value);
                return;
            }
        }
        $setting = new Setting();
        $setting->setName($name);
        $setting->setValue($value);
        $this->settings->add($setting);
        return $this;
    }

    /**
     * @param WebSiteSetting $value
     * @return Settings
     */
    public function setWebSite($value)
    {
        return $this->setSetting('webSite', $value);
    }

    /**
     * @return WebSiteSetting|null
     */
    public function getWebSite()
    {
        return $this->getSetting('webSite');
    }
}
