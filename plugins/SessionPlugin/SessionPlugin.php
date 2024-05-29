<?php

use Local\Modules\Session\SessionHandler;
use Local\WebApp\Plugins\AbstractWebAppPlugin;
use Local\WebApp\WebApp;
use Symfony\Component\Yaml\Parser;

/**
 * Session Plugin
 *
 * @version 0.1
 */
class SessionPlugin extends AbstractWebAppPlugin
{
    /**
     * API version used by this plugin
     *
     * @var int
     */
    const API_VERSION = 3;
    protected $enabled = true;
    protected $dependsOn = array();
    protected $isSessionable = false;
    /**
     * @var mixed
     */
    protected $sessionHandler;

    public function __construct(WebApp $pico)
    {
        parent::__construct($pico);
        $this->sessionHandler = $this->container->get(SessionHandler::class);
    }
    public function isOpenPage(string $page): bool
    {
        return in_array($page, $this->pico->getConfig('open_pages') ?? []);
    }

    public function isAuthorizationAttempt(): bool
    {
        return $this->pico->getRequestUrl() === 'login' && isset($_POST['username']) && isset($_POST['password']);
    }

    public function onPluginsLoaded(array $plugins)
    {
        $this->logger->debug('SessionPlugin::onPluginsLoaded()');
    }

    public function onPluginManuallyLoaded($plugin)
    {
        $this->logger->debug('SessionPlugin::onPluginManuallyLoaded()', ['plugin' => $plugin]);
    }

    public function onConfigLoaded(array &$config)
    {
        $this->logger->debug('SessionPlugin::onConfigLoaded()', ['open_pages' => $this->pico->getConfig('open_pages'),'config' => $config]);
        $this->isSessionable = $this->pico->getConfig('open_pages') !== null;
    }

    public function onThemeLoading(&$theme)
    {
        $this->logger->debug('SessionPlugin::onThemeLoading()', ['theme' => $theme]);
    }

    public function onThemeLoaded($theme, $themeApiVersion, array &$themeConfig)
    {
        $this->logger->debug('SessionPlugin::onThemeLoaded()', ['theme' => $theme, 'themeApiVersion' => $themeApiVersion, 'themeConfig' => $themeConfig]);
    }

    public function onRequestUrl(&$url)
    {
        $this->logger->debug('SessionPlugin::onRequestUrl()', ['url' => $url, 'post' => $_POST]);

        if (!$this->isSessionable) {
            $this->logger->debug('SessionPlugin::onRequestUrl() - Not sessionable');
            return;
        }

        $currentUrl = $url === '' ? 'index' : $url;

        if ($this->isOpenPage($currentUrl)) {
            $this->logger->debug('SessionPlugin::onRequestUrl() - Open page');
            if ($this->isAuthorizationAttempt()) {
                $this->logger->debug('SessionPlugin::onRequestUrl() - Authorization attempt');
               if ($this->sessionHandler->create($_POST['username'], $_POST['password'])) {
                   $redirect = $this->sessionHandler->get('redirect') ?? '';
                   $this->logger->debug('SessionPlugin::onRequestUrl() - Redirecting to given page', ['redirect' => $redirect]);
                   header('Location: ' . (strlen($redirect) > 0 ? '/?' . $redirect : '/'));
               } else {
                     $this->logger->debug('SessionPlugin::onRequestUrl() - Session creation failed');
               }
            } else {
                $this->logger->debug('SessionPlugin::onRequestUrl() - Not authorization attempt');
            }
        } else {
            $this->logger->debug('SessionPlugin::onRequestUrl() - Not open page');
            if ($this->sessionHandler->get('user') === null) {
                $this->sessionHandler->set('redirect', $url);
                header('Location: /?login');
                exit;
            } else {
                if ($currentUrl === 'logout') {
                    $this->sessionHandler->destroy();
                    header('Location: /?login');
                    exit;
                }
            }
        }
    }

    /**
     * Triggered after Pico has discovered the content file to serve
     *
     * @param string &$file absolute path to the content file to serve
     * @see Pico::getRequestFile()
     *
     * @see Pico::resolveFilePath()
     */
    public function onRequestFile(&$file)
    {
        $this->pico->getLogger()->debug('SessionPlugin::onRequestFile()', ['file' => $file, 'open_pages' => $this->pico->getConfig('open_pages')]);
        // your code
    }

    /**
     * Triggered before Pico reads the contents of the file to serve
     *
     * @see Pico::loadFileContent()
     * @see DummyPlugin::onContentLoaded()
     */
    public function onContentLoading()
    {
        // your code
    }

    /**
     * Triggered before Pico reads the contents of a 404 file
     *
     * @see Pico::load404Content()
     * @see DummyPlugin::on404ContentLoaded()
     */
    public function on404ContentLoading()
    {
        // your code
    }

    /**
     * Triggered after Pico has read the contents of the 404 file
     *
     * @param string &$rawContent raw file contents
     * @see Pico::getRawContent()
     * @see Pico::is404Content()
     *
     * @see DummyPlugin::on404ContentLoading()
     */
    public function on404ContentLoaded(&$rawContent)
    {
        // your code
    }

    /**
     * Triggered after Pico has read the contents of the file to serve
     *
     * If Pico serves a 404 file, this event is triggered with the raw contents
     * of said 404 file. Use {@see Pico::is404Content()} to check for this
     * case when necessary.
     *
     * @param string &$rawContent raw file contents
     * @see Pico::getRawContent()
     * @see Pico::is404Content()
     *
     * @see DummyPlugin::onContentLoading()
     */
    public function onContentLoaded(&$rawContent)
    {
        // your code
    }

    /**
     * Triggered before Pico parses the meta header
     *
     * @see Pico::parseFileMeta()
     * @see DummyPlugin::onMetaParsed()
     */
    public function onMetaParsing()
    {
        // your code
    }

    /**
     * Triggered after Pico has parsed the meta header
     *
     * @param string[] &$meta parsed meta data
     * @see Pico::getFileMeta()
     *
     * @see DummyPlugin::onMetaParsing()
     */
    public function onMetaParsed(array &$meta)
    {
        // your code
    }

    /**
     * Triggered before Pico parses the pages content
     *
     * @see Pico::prepareFileContent()
     * @see Pico::substituteFileContent()
     * @see DummyPlugin::onContentPrepared()
     * @see DummyPlugin::onContentParsed()
     */
    public function onContentParsing()
    {
        // your code
    }

    /**
     * Triggered after Pico has prepared the raw file contents for parsing
     *
     * @param string &$markdown Markdown contents of the requested page
     * @see Pico::parseFileContent()
     * @see DummyPlugin::onContentParsed()
     *
     * @see DummyPlugin::onContentParsing()
     */
    public function onContentPrepared(&$markdown)
    {
        // your code
    }

    /**
     * Triggered after Pico has parsed the contents of the file to serve
     *
     * @param string &$content parsed contents (HTML) of the requested page
     * @see DummyPlugin::onContentPrepared()
     * @see Pico::getFileContent()
     *
     * @see DummyPlugin::onContentParsing()
     */
    public function onContentParsed(&$content)
    {
        // your code
    }

    /**
     * Triggered before Pico reads all known pages
     *
     * @see DummyPlugin::onPagesDiscovered()
     * @see DummyPlugin::onPagesLoaded()
     */
    public function onPagesLoading()
    {
    }

    /**
     * Triggered before Pico loads a single page
     *
     * Set the `$skipFile` parameter to TRUE to remove this page from the pages
     * array. Pico usually passes NULL by default, unless it is a conflicting
     * page (i.e. `content/sub.md`, but there's also a `content/sub/index.md`),
     * then it passes TRUE. Don't change this value incautiously if it isn't
     * NULL! Someone likely set it to TRUE or FALSE on purpose...
     *
     * @param string $id relative path to the content file
     * @param bool|null $skipPage set this to TRUE to remove this page from the
     *     pages array, otherwise leave it unchanged
     * @see DummyPlugin::onSinglePageContent()
     * @see DummyPlugin::onSinglePageLoaded()
     *
     */
    public function onSinglePageLoading($id, &$skipPage)
    {
        if(!array_key_exists('user', $_SESSION) || $_SESSION['user'] === null && !in_array($id, $this->pico->getConfig('open_pages')) && $skipPage === null) {
            $skipPage = true;
        }
    }

    /**
     * Triggered when Pico loads the raw contents of a single page
     *
     * Please note that this event isn't triggered when the currently processed
     * page is the requested page. The reason for this exception is that the
     * raw contents of this page were loaded already.
     *
     * @param string $id relative path to the content file
     * @param string &$rawContent raw file contents
     * @see DummyPlugin::onSinglePageLoading()
     * @see DummyPlugin::onSinglePageLoaded()
     *
     */
    public function onSinglePageContent($id, &$rawContent)
    {
        // your code
    }

    /**
     * Triggered when Pico loads a single page
     *
     * Please refer to {@see Pico::readPages()} for information about the
     * structure of a single page's data.
     *
     * @param array &$pageData data of the loaded page
     * @see DummyPlugin::onSinglePageContent()
     *
     * @see DummyPlugin::onSinglePageLoading()
     */
    public function onSinglePageLoaded(array &$pageData)
    {
        $this->logger->debug('SessionPlugin::onSinglePageLoaded()', ['pageData' => $pageData]);
    }

    /**
     * Triggered after Pico has discovered all known pages
     *
     * Pico's pages array isn't sorted until the `onPagesLoaded` event is
     * triggered. Please refer to {@see Pico::readPages()} for information
     * about the structure of Pico's pages array and the structure of a single
     * page's data.
     *
     * @param array[] &$pages list of all known pages
     * @see DummyPlugin::onPagesLoaded()
     *
     * @see DummyPlugin::onPagesLoading()
     */
    public function onPagesDiscovered(array &$pages)
    {
        // your code
    }

    /**
     * Triggered after Pico has sorted the pages array
     *
     * Please refer to {@see Pico::readPages()} for information about the
     * structure of Pico's pages array and the structure of a single page's
     * data.
     *
     * @param array[] &$pages sorted list of all known pages
     * @see DummyPlugin::onPagesDiscovered()
     * @see Pico::getPages()
     *
     * @see DummyPlugin::onPagesLoading()
     */
    public function onPagesLoaded(array &$pages)
    {
        $openPages = $this->pico->getConfig('open_pages');
        $loggedIn = array_key_exists('user', $_SESSION) && $_SESSION['user'] !== null;
        $roles = $loggedIn && array_key_exists('roles', $_SESSION['user']) ? $_SESSION['user']['roles'] : [];

        $filteredPages = [];
        foreach ($pages as $id => $page) {
            $pageRoles = $page['meta']['roles'] === "" ? [] : $page['meta']['roles'];
            $this->logger->debug('SessionPlugin::onPagesLoaded()', ['id' => $id, 'pageRoles' => $pageRoles, 'roles' => $roles, 'loggedIn' => $loggedIn, 'openPages' => $openPages]);
            if($loggedIn && (count($pageRoles) == 0 || count(array_intersect($roles, $pageRoles)) > 0) || in_array($id, $openPages)) {
                $filteredPages[$id] = $page;
            }
        }
        $pages = $filteredPages;
        $this->logger->debug('SessionPlugin::onPagesLoaded()', ['pages' => $pages]);
    }

    /**
     * Triggered when Pico discovered the current, previous and next pages
     *
     * If Pico isn't serving a regular page, but a plugin's virtual page, there
     * will neither be a current, nor previous or next pages. Please refer to
     * {@see Pico::readPages()} for information about the structure of a single
     * page's data.
     *
     * @param array|null &$currentPage data of the page being served
     * @param array|null &$previousPage data of the previous page
     * @param array|null &$nextPage data of the next page
     * @see Pico::getCurrentPage()
     * @see Pico::getPreviousPage()
     * @see Pico::getNextPage()
     *
     */
    public function onCurrentPageDiscovered(
        array &$currentPage = null,
        array &$previousPage = null,
        array &$nextPage = null
    )
    {
        // your code
    }

    /**
     * Triggered after Pico built the page tree
     *
     * Please refer to {@see Pico::buildPageTree()} for information about
     * the structure of Pico's page tree array.
     *
     * @param array  &$pageTree page tree
     * @see Pico::getPageTree()
     *
     */
    public function onPageTreeBuilt(array &$pageTree)
    {
        // your code
    }

    /**
     * Triggered before Pico renders the page
     *
     * @param string &$templateName file name of the template
     * @param array  &$twigVariables template variables
     * @see DummyPlugin::onPageRendered()
     *
     */
    public function onPageRendering(&$templateName, array &$twigVariables)
    {
        $twigVariables['loggedInUser'] = $_SESSION['user'] ?? '';
        $twigVariables['openPages'] = $this->pico->getConfig('open_pages');
    }

    /**
     * Triggered after Pico has rendered the page
     *
     * @param string &$output contents which will be sent to the user
     * @see DummyPlugin::onPageRendering()
     *
     */
    public function onPageRendered(&$output)
    {
        // your code
    }

    /**
     * Triggered when Pico reads its known meta header fields
     *
     * @param string[] &$headers list of known meta header fields; the array
     *     key specifies the YAML key to search for, the array value is later
     *     used to access the found value
     * @see Pico::getMetaHeaders()
     *
     */
    public function onMetaHeaders(array &$headers)
    {
        $this->logger->debug('SessionPlugin::onMetaHeaders()', ['headers' => $headers]);
    }

    /**
     * Triggered when Pico registers the YAML parser
     *
     * @param Parser &$yamlParser YAML parser instance
     * @see Pico::getYamlParser()
     *
     */
    public function onYamlParserRegistered(Parser &$yamlParser)
    {
        // your code
    }

    /**
     * Triggered when Pico registers the Parsedown parser
     *
     * @param Parsedown &$parsedown Parsedown instance
     * @see Pico::getParsedown()
     *
     */
    public function onParsedownRegistered(Parsedown &$parsedown)
    {
        // your code
    }

    /**
     * Triggered when Pico registers the twig template engine
     *
     * @param Twig_Environment &$twig Twig instance
     * @see Pico::getTwig()
     *
     */
    public function onTwigRegistered(Twig_Environment &$twig)
    {
        // your code
    }
}
