<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Base;

use LFramework\Base\Component as Component;
use LFramework\Exception\InvalidArgumentException as InvalidArgumentException;
use LFramework\Exception\InvalidConfigException as InvalidConfigException;
use LFramework\Exception\InvalidCalException as InvalidCallException;
use LFramework\Helpers\ArrayMethods as ArrayMethods;
use LFramework\Helpers\Html as Html;
use LFramework\Base\ViewContextInterface as ViewContextInterface;

/**
 * Description of View
 *
 * @author Lens
 */
class View extends Component
{

    const EVENT_BEFORE_RENDER = "lframework.view.render.defore";
    const EVENT_AFTER_RENDER = "lframework.view.render.after";
    const EVENT_BEGIN_PAGE = "beginPage";
    const EVENT_BEGIN_BODY = "beginBody";
    const EVENT_END_BODY = "endBody";
    const EVENT_END_PAGE = "endPage";
    
    const POS_HEAD = 1;
    const POS_BEGIN = 2;
    const POS_END = 3;
    
    const PH_HEAD = "<![CDATA[LFRAMEWORK-BLOCK-HEAD]]>";
    const PH_BODY_BEGIN = "<![CDATA[LFRAMEWORK-BLOCK-BODY-BEGIN]]>";
    const PH_BODY_END = "<![CDATA[LFRAMEWORK-BLOCK-BODY-END]]>";
   
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_title;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_metaTags = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_linkTags = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_cssFiles = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_jsFiles = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_assetBundles = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_parameters = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_defaultExtension = "php"; 
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_context;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_viewFiles = array();
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_assetManager;
   
    
    public function beginPage()
    {
        ob_start();
        ob_implicit_flush(false);

        $this->trigger(self::EVENT_BEGIN_PAGE);
    }
    public function head()
    {
         echo self::PH_HEAD;
    }
    public function beginBody()
    {
        echo self::PH_BODY_BEGIN;
        $this->trigger(self::EVENT_BEGIN_BODY);
    }
    public function endBody()
    {
        $this->trigger(self::EVENT_END_BODY);
        echo self::PH_BODY_END;

        foreach (array_keys($this->_assetBundles) as $bundle) {
            $this->registerAssetFiles($bundle);
        }
    }
    public function endPage()
    {
        $this->trigger(self::EVENT_END_PAGE);

        $content = ob_get_clean();

        $content = strtr($content, [
            self::PH_HEAD => $this->_renderHeadHtml(),
            self::PH_BODY_BEGIN => $this->_renderBodyBeginHtml(),
            self::PH_BODY_END => $this->_renderBodyEndHtml(),
        ]);
        $this->_clear();
        
        echo $content;
    }
    protected function _clear()
    {
        $this->_metaTags = null;
        $this->_linkTags = null;
        $this->_cssFiles = null;
        $this->_jsFiles = null;
        $this->_assetBundles = array();
    }
    public function getAssetManager()
    {
        return $this->_assetManager ?: \LFramework::$application->getAssetManager();
    }
    public function registerMetaTag($options, $key = null)
    {
        if ($key === null) {
            $this->_metaTags[] = Html::tag('meta', '', $options);
        } else {
            $this->_metaTags[$key] = Html::tag('meta', '', $options);
        }
    }
    public function registerLinkTag($options, $key = null)
    {
        if ($key === null) {
            $this->_linkTags[] = Html::tag('link', '', $options);
        } else {
            $this->_linkTags[$key] = Html::tag('link', '', $options);
        }
    }
    public function registerCssFile($url, $options = array(), $key = null)
    {
        $key = $key ?: $url;
        $depends = ArrayMethods::remove($options, 'depends');

        if (empty($depends)) {
            $this->_cssFiles[$key] = Html::cssFile($url, $options);
        } else {
            $this->getAssetManager()->getBundles[$key] = new AssetBundle([
                'baseUrl' => '',
                'css' => [strncmp($url, '//', 2) === 0 ? $url : ltrim($url, '/')],
                'cssOptions' => $options,
                'depends' => (array) $depends,
            ]);
            $this->registerAssetBundle($key);
        }
    }
    public function registerJsFile($url, $options = array(), $key = null)
    {
        $key = $key ?: $url;
        $depends = ArrayMethods::remove($options, 'depends');

        if (empty($depends)) {
            $position = ArrayMethods::remove($options, 'position', self::POS_END);
            $this->_jsFiles[$position][$key] = Html::jsFile($url, $options);
        } else {
            $this->getAssetManager()->getBundles[$key] = new AssetBundle([
                'baseUrl' => '',
                'js' => [strncmp($url, '//', 2) === 0 ? $url : ltrim($url, '/')],
                'jsOptions' => $options,
                'depends' => (array) $depends,
            ]);
            $this->registerAssetBundle($key);
        }
    }
    protected function _renderHeadHtml()
    {
         $lines = [];
        if (!empty($this->_metaTags)) {
            $lines[] = implode("\n", $this->_metaTags);
        }
        if (!empty($this->_linkTags)) {
            $lines[] = implode("\n", $this->_linkTags);
        }
        if (!empty($this->_cssFiles)) {
            $lines[] = implode("\n", $this->_cssFiles);
        }
        if (!empty($this->_jsFiles[self::POS_HEAD])) {
            $lines[] = implode("\n", $this->_jsFiles[self::POS_HEAD]);
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }
    protected function _renderBodyBeginHtml()
    {
        $lines = [];
        if (!empty($this->_jsFiles[self::POS_BEGIN])) {
            $lines[] = implode("\n", $this->_jsFiles[self::POS_BEGIN]);
        }
        return empty($lines) ? '' : implode("\n", $lines);
    }
    protected function _renderBodyEndHtml()
    {
        $lines = [];

        if (!empty($this->_jsFiles[self::POS_END])) {
            $lines[] = implode("\n", $this->_jsFiles[self::POS_END]);
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }
    protected function _findViewFile($view, $context = null)
    {
        if (strncmp($view, "//", 2) === 0) {
            // e.g. "//layouts/main"
            $file = \LFramework::$application->getViewPath(). DS. ltrim($view, "//");
        } elseif (strncmp($view, "/", 1) === 0) {
            // e.g. "/site/index"
            if (\LFramework::$application->getController() !== null) {
                $file = \LFramework::$application->getController()->getModule()->
                    getViewPath(). DS. ltrim($view, "/");
            } else {
                throw new InvalidCallException("Unable to locate view file for view {$view}"
                . ": no active controller.");
            }
        } elseif ($context instanceof ViewContextInterface) {
            $file = $context->getViewPath(). DS. $view;
        } elseif (($currentViewFile = $this->getViewFile()) !== false) {
            $file = dirname($currentViewFile) . DS . $view;
        } else {
            throw new InvalidCallException("Unable to resolve view file for view '$view': no active view context. file = {$file}");
        }
        
        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }
        
        $path = $file . "." . $this->_defaultExtension;
        if ($this->_defaultExtension !== "php" && !is_file($path)) {
            $path = $file . ".php";
        }

        return $path;
    }
    public function render($view, $parameters = array(), $context = null)
    {
        $viewFile = $this->_findViewFile($view, $context);
        
        return $this->renderFile($viewFile, $parameters, $context);
    }   
    public function renderFile($viewFile, $parameters = array(), $context = null)
    {
        if (file_exists($viewFile)) {
            $viewFile = $this->_localize($viewFile);
        } else {
            throw new InvalidArgumentException("The view file does not exist: $viewFile");
        }
        
        $oldContext = $this->_context;
        if ($context !== null) {
            $this->_context = $context;
        }
        $output = "";
        $this->_viewFiles[] = $viewFile;
        
        $this->trigger(self::EVENT_BEFORE_RENDER, array($viewFile, $parameters));
        
        ob_start();
        ob_implicit_flush(false);
        extract($parameters, EXTR_OVERWRITE);
        require($viewFile);
        $output = ob_get_clean();
        
        $this->trigger(self::EVENT_AFTER_RENDER, array($viewFile, $parameters));
        
        array_pop($this->_viewFiles);
        $this->_context = $oldContext;
        
        return $output;
    }
    public function renderAjax($view, $parameters = array())
    {
        $viewFile = $this->_findViewFile($view);

        ob_start();
        ob_implicit_flush(false);

        $this->beginPage();
        $this->head();
        $this->beginBody();
        echo $this->renderFile($viewFile, $parameters);
        $this->endBody();
        $this->endPage();

        return ob_get_clean();
    }
    public function getViewFile()
    {
        return end($this->_viewFiles);
    }
    protected function _localize($viewFile, $language = null, $sourceLanguage = null)
    {
        if ($language === null) {
            $language = \LFramework::$application->getLanguage();
        }
        if ($sourceLanguage === null) {
            $sourceLanguage = \LFramework::$application->getSourceLanguage();
        }
        if ($language === $sourceLanguage) {
            return $viewFile;
        }
        $desiredFile = dirname($viewFile) . DIRECTORY_SEPARATOR . $language . 
            DIRECTORY_SEPARATOR . basename($viewFile);
        if (is_file($desiredFile)) {
            return $desiredFile;
        } else {
            $language = substr($language, 0, 2);
            if ($language === $sourceLanguage) {
                return $viewFile;
            }
            $desiredFile = dirname($viewFile) . DIRECTORY_SEPARATOR . $language . 
                DIRECTORY_SEPARATOR . basename($viewFile);

            return is_file($desiredFile) ? $desiredFile : $viewFile;
        }
    }
    protected function registerAssetFiles($assetBundle)
    {
         if (!isset($this->_assetBundles[$assetBundle])) {
            return;
        }
        $bundle = $this->_assetBundles[$assetBundle];
        if ($bundle) {
            foreach ($bundle->getDepends() as $dep) {
                $this->registerAssetFiles($dep);
            }
            $bundle->registerAssetFiles($this);
        }
        unset($this->_assetBundles[$assetBundle]);
    }
    public function registerAssetBundle($name, $position = null) 
    {
        if (!isset($this->_assetBundles[$name])) {
            $bundle = $this->getAssetManager()->getBundle($name);
            $this->_assetBundles[$name] = false;
            // register dependencies
            $pos = isset($bundle->_jsOptions['position']) ? $bundle->_jsOptions['position'] : null;
            foreach ($bundle->getDepends() as $dep) {
                $this->registerAssetBundle($dep, $pos);
            }
            $this->_assetBundles[$name] = $bundle;
        } elseif ($this->_assetBundles[$name] === false) {
            throw new InvalidConfigException("A circular dependency is detected for bundle {$name}.");
        } else {
            $bundle = $this->_assetBundles[$name];
        }
        if ($position !== null) {
            $pos = isset($bundle->_jsOptions['position']) ? $bundle->_jsOptions['position'] : null;
            if ($pos === null) {
                $bundle->_jsOptions['position'] = $pos = $position;
            } elseif ($pos > $position) {
                throw new InvalidConfigException("An asset bundle that depends on {$name}"
                . " has a higher javascript file position configured than '$name'.");
            }
            // update position for all dependencies
            foreach ($bundle->getDepends() as $dep) {
                $this->registerAssetBundle($dep, $pos);
            }
        }
        return $bundle;
    }
    public function errors($array, $key, $separator = "<br/>", $before = "", $after = "") 
    {
        if (isset($array[$key])) {
            return $before.$separator.$array[$key].$separator.$after;
        }
        
        return "";
    }
    
    
}
