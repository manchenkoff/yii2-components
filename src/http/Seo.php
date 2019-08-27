<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\http;

use yii\base\BaseObject;
use yii\web\View;

/**
 * Class for simplifying work with SEO and meta HTML tags
 * @property array $config
 * @property string $logo
 * @property string $name
 * @property string $title
 */
class Seo extends BaseObject
{
    /**
     * @var View Current view instance
     */
    private $_view;

    /**
     * @var string Website main title (name)
     */
    private $_name;

    /**
     * @var string Relative path to website logo
     */
    private $_logo;

    /**
     * @var array <meta> tags array
     */
    private $meta = [];

    /**
     * @var array <link> tags array
     */
    private $links = [];

    /**
     * Initial loading when controller view was set
     */
    public function init()
    {
        // get current controller view
        $this->_view = app()->controller->view;

        // register all of SEO tags
        $this->registerData();

        // set new page title by magic-method
        $this->title = 'Undefined';
    }

    /**
     * Register all of the tags from configuration
     */
    private function registerData()
    {
        $this->links[] = [
            'rel' => 'canonical',
            'href' => url(request()->pathInfo, true),
        ];

        foreach ($this->links as $link) {
            $this->_view->registerLinkTag($link, $link['rel']);
        }

        foreach ($this->meta as $meta) {
            $this->_view->registerMetaTag($meta, $meta['name']);
        }
    }

    /**
     * Registers new page SEO data (for using in controllers)
     *
     * @param string $title
     * @param string $description
     * @param string $keywords
     * @param bool $useOpenGraph
     * @param string|null $image
     */
    public function registerPage(string $title, string $description, string $keywords, bool $useOpenGraph = false, string $image = null)
    {
        $this->title = $title;

        $this->meta[] = [
            'name' => 'description',
            'content' => $description,
        ];

        $this->meta[] = [
            'name' => 'keywords',
            'content' => $keywords,
        ];

        $this->registerData();

        if ($useOpenGraph) {
            $this->registerOpenGraph(
                $title,
                $description,
                $image
            );
        }
    }

    /**
     * Register OpenGraph main snippet card
     *
     * @param string|null $title
     * @param string|null $description
     * @param string|null $image
     */
    public function registerOpenGraph(string $title = null, string $description = null, string $image = null)
    {
        $params = [
            'og:url' => url(request()->url, true),
            'og:type' => 'article', // fixed
            'og:title' => $title ?? $this->title,
            'og:description' => $description ?? $this->get('description'),
            'og:image' => $image ?? $this->_logo,
            'og:site_name' => $this->_name,
        ];

        foreach ($params as $name => $content) {
            $this->_view->registerMetaTag([
                'name' => $name,
                'content' => $content,
            ], $name);
        }
    }

    /**
     * Returns SEO item value by selected key
     *
     * @param string $key
     *
     * @return null
     */
    public function get(string $key)
    {
        $value = null;

        // try to find in <meta> tags
        foreach ($this->meta as $meta) {
            if ($meta['name'] == $key) {
                $value = $meta['content'];
                break;
            }
        }

        // if not found yet
        if (is_null($value)) {
            // scan <link> tags
            foreach ($this->links as $link) {
                if ($link['rel'] == $key) {
                    $value = $link['href'];
                    break;
                }
            }
        }

        return $value;
    }

    /**
     * Magic method for loading configuration file (see config/main.php)
     *
     * @param array $config
     */
    protected function setConfig(array $config)
    {
        $this->_name = $config['name'];
        $this->meta = $config['meta'];
        $this->links = $config['links'];
        $this->_logo = $config['logo'];
    }

    /**
     * Returns title of the current View
     * @return string
     */
    protected function getTitle()
    {
        return $this->_view->title;
    }

    /**
     * Sets new title with concatenating website name like: "Page | Website" (if exists)
     *
     * @param string $value
     */
    protected function setTitle(string $value)
    {
        $this->_view->title = ($this->_name) ? "{$value} | {$this->_name}" : $value;
    }

    /**
     * Returns website name
     * @return string
     */
    protected function getName()
    {
        return $this->_name;
    }

    /**
     * Returns website logo
     * @return string
     */
    protected function getLogo()
    {
        return $this->_logo;
    }
}