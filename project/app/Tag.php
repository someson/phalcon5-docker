<?php

namespace App;

use Phalcon\Di\FactoryDefault;
use Phalcon\Tag as BaseTag;

class Tag extends BaseTag
{
    static protected array $_documentMeta = [];
    static protected array $_alternate = [];
    static protected string $_canonical;

    /**
     * Builds a meta tag and adds to an assoc array
     * <code>
     * Library\Tag::setMeta('description', ['name' => 'description', 'content' => 'lorem ipsum']);
     * </code>
     */
    public static function setMeta(string $key, array $params): void
    {
        if (\count($params)) {
            self::$_documentMeta[$key] = self::tagHtml('meta', $params, true, false, true);
        }
    }

    /**
     * <code>
     * Library\Tag::getMeta('description');
     * </code>
     */
    public static function getMeta($key): string
    {
        if (isset(self::$_documentMeta[$key])) {
            return self::$_documentMeta[$key];
        }
        if (Env::isDevelopment()) {
            $logger = FactoryDefault::getDefault()?->getShared('logger');
            $logger->warning(sprintf('Page: %s - meta %s not defined', $_SERVER['REQUEST_URI'], $key));
            return sprintf("<!-- Error: meta %s not defined -->\n", htmlspecialchars($key));
        }
        return '';
    }

    public static function setCanonical(string $url): void
    {
        $attr = ['rel' => 'canonical', 'href' => $url];
        self::$_canonical = self::tagHtml('link', $attr, true, false, true);
    }

    public function getCanonical(): string
    {
        return self::$_canonical ?? '';
    }

    /**
     * <code>
     * Library\Tag::setAlternate([
     *    'de' => 'https://example.de/deutsch',
     *    'en' => 'https://example.com/english',
     * ]);
     * </code>
     */
    public static function setAlternate(array $langToUrl): void
    {
        foreach ($langToUrl as $lang => $url) {
            $attr = [
                'rel' => 'alternate',
                'hreflang' => $lang,
                'href' => $url,
            ];
            self::$_alternate[$lang] = self::tagHtml('link', $attr, true, false, true);
        }
    }

    /**
     * <code>
     * Library\Tag::getAlternate();
     * </code>
     *
     * Rresults:
     * <link rel="alternate" hreflang="de" href="https://example.de/deutsch">
     * <link rel="alternate" hreflang="en" href="https://example.com/english">
     */
    public function getAlternate(): string
    {
        return \count(self::$_alternate) > 1 ? implode('', self::$_alternate) : '';
    }

    public static function removeAlternate(string $lang = null): void
    {
        if ($lang !== null) {
            if (isset(self::$_alternate[$lang])) {
                unset(self::$_alternate[$lang]);
            }
        } else {
            self::$_alternate = [];
        }
    }
}
