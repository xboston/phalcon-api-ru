<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">

    <title></title>

    <link href='http://fonts.googleapis.com/css?family=Ubuntu&amp;subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" media="all" href="css/style.css?v=1"/>

</head>
<body>

<div class="wrapper">

<div class="size-wrap">

    <div class="header clear-fix">
        <a class="header-logo" href="#">Phalcon</a>

        <div class="header-right">
            <iframe src="http://ghbtns.com/github-btn.html?user=phalcon&amp;repo=cphalcon&amp;type=watch&amp;count=true&amp;size=large"
                    allowtransparency="true" frameborder="0" scrolling="0" width="130px" height="30px"></iframe>
        </div>
        <ul class="header-nav">

            <li><a class="header-nav-link" href="#">Download</a></li>
            <li><a class="header-nav-link" href="#">Documentation</a></li>
            <li><a class="header-nav-link" href="#">Support</a></li>
            <li><a class="header-nav-link" href="#">Blog</a></li>
            <li><a class="header-nav-link" href="#">Get Involved</a></li>
        </ul>
    </div>
</div>


<div class="header-line">
    <div class="size-wrap">
        <div class="header-line-title title-white">Documentation</div>
    </div>
</div>

<div class="size-wrap">

<div class="note">Phalcon's documentation is maintained in <a href="#">Github repositories</a>. You are more than welcome to send us pull requests if you found an error or if</div>

<ul class="tabs clear-fix">
    <li><a class="tab-link" href="#"><u>Overview</u></a></li>
    <li><a class="tab-link active" href="#"><u>API Reference</u></a></li>
</ul>

<div class="content-wrap clear-fix">

<div class="sidebar">

    <div class="searchbox-wrap">
        <input type="text" name="q" id="searchbox" class="searchbox" placeholder="Search"/>
        <input type="submit" class="search-button"/>
    </div>


    <ul class="api-table-of-contents">
        <li><a href="#" class="api-top-level-link">Acl</a></li>
        <li><a href="#" class="api-top-level-link">Annotations</a></li>
        <li><a href="#" class="api-top-level-link">Assets</a></li>
        <li>
            <a href="#" class="api-top-level-link active">Cache</a>
            <ul class="api-sublist dash-list">
                <li>
                    <a class="api-link active" href="#">Backend</a>
                    <ul class="api-sublist">
                        <li><a href="#" class="api-link">Apc</a></li>
                        <li><a href="#" class="api-link">File</a></li>
                        <li><a href="#" class="api-link">Memcache</a></li>
                        <li><a href="#" class="api-link">Memory</a></li>
                        <li><a href="#" class="api-link">Mongo</a></li>
                    </ul>
                </li>
                <li><a class="api-link" href="#">Exeption</a></li>
                <li>
                    <a class="api-link" href="#">Frontend</a>
                    <ul class="api-sublist">
                        <li><a href="#" class="api-link">Base64</a></li>
                        <li><a href="#" class="api-link">Data</a></li>
                        <li><a href="#" class="api-link">Json</a></li>
                        <li><a href="#" class="api-link">None</a></li>
                        <li><a href="#" class="api-link">Output</a></li>
                    </ul>
                </li>
                <li><a class="api-link" href="#">Multiple</a></li>
            </ul>
        </li>
        <li><a href="#" class="api-top-level-link">CLI</a></li>
        <li><a href="#" class="api-top-level-link">Config</a></li>
        <li><a href="#" class="api-top-level-link">Crypt</a></li>
        <li><a href="#" class="api-top-level-link">Db</a></li>
        <li><a href="#" class="api-top-level-link">DI</a></li>
        <li><a href="#" class="api-top-level-link">Escaper</a></li>
        <li><a href="#" class="api-top-level-link">Events</a></li>
        <li><a href="#" class="api-top-level-link">Filter</a></li>
        <li><a href="#" class="api-top-level-link">Flash</a></li>
        <li><a href="#" class="api-top-level-link">Forms</a></li>
        <li><a href="#" class="api-top-level-link">Http</a></li>
        <li><a href="#" class="api-top-level-link">Loader</a></li>
        <li><a href="#" class="api-top-level-link">Logger</a></li>
        <li><a href="#" class="api-top-level-link">Mvc</a></li>
        <li><a href="#" class="api-top-level-link">Paginator</a></li>
        <li><a href="#" class="api-top-level-link">Queue</a></li>
        <li><a href="#" class="api-top-level-link">Security</a></li>
        <li><a href="#" class="api-top-level-link">Session</a></li>
        <li><a href="#" class="api-top-level-link">Tag</a></li>
        <li><a href="#" class="api-top-level-link">Translate</a></li>
        <li><a href="#" class="api-top-level-link">Validation</a></li>
    </ul>

</div>

<div class="content">

    <h1><span class="lighter"><?php echo $classData['type'] ?></span> <?php echo $classData['name'] ?></h1>

    <table class="api-info-table">

        <tr>
            <th>Namespace:</th>
            <td><a href="#"><?php echo $classData['namespace'] ?></a></td>
        </tr>

        <?php if ( $classData['implements'] ): ?>
            <tr>
                <th>Implements:</th>
                <td>
                    <?php foreach ( $classData['implements'] as $implements ): ?>
                        <a href="#"><?php echo $implements ?></a>
                    <?php endforeach ?>
                </td>
            </tr>
        <?php endif ?>

        <?php if ( $classData['extends'] ): ?>
            <tr>
                <th>Extends:</th>
                <td><a href="#"><?php echo $classData['extends'] ?></a></td>
            </tr>
        <?php endif ?>


    </table>

    <?php if ( $classData['description'] ): ?>
        <div class="note"><?php echo $classData['description'] ?></div>
    <?php endif ?>

    <h2>Subclasses</h2>

    <ul class="two-columns">
        <li><a href="#">Phalcon\Cache\Backend\<span class="strong">Apc</span></a></li>
        <li><a href="#">Phalcon\Cache\Backend\<span class="strong">File</span></a></li>
        <li><a href="#">Phalcon\Cache\Backend\<span class="strong">Memcache</span></a></li>
        <li><a href="#">Phalcon\Cache\Backend\<span class="strong">Memory</span></a></li>
        <li><a href="#">Phalcon\Cache\Backend\<span class="strong">Mongo</span></a></li>
    </ul>

    <?php if ( $classData['constants'] ): ?>
        <h2>Constants</h2>

        <ul class="two-columns">
            <?php foreach ( $classData['constants'] as $value=>$type ): ?>
                <li><?php echo $type . ': ' . $value ?></li>
            <?php endforeach ?>

        </ul>

    <?php endif ?>

    <?php if($classData['methods']): ?>
    <h2>Methods summary</h2>

    <table class="api-methods-table">

        <?php foreach($classData['methods'] as $method): ?>
        <tr>
            <td class="method"><span class="method-type public" data-tooltip="public"></span> <a href="#"><?php echo $method['name'] ?></a></td>
            <td class="description"><?php echo $method['description'] ?></td>
        </tr>
        <?php endforeach ?>

    </table>
    <?php endif ?>



    <h2>Method details</h2>


    <h3>__construct</h3>

    <p>

    <div class="highlight">
                    <pre>
public __construct ($frontend, $options = null )
                    </pre>
    </div>
    </p>

    <p>Phalcon\Cache\Backend constructor</p>

    <h4>Parameters:</h4>

    <ul>
        <li><span class="strong">$frontend</span> <a href="#">Phalcon\Cache\FrontendInterface</a><br/>the property name or the event name</li>
        <li><span class="strong">$options</span> array</li>
    </ul>


    <h4>Returns:</h4>

    <p>mixed the behavior object, or null if the behavior does not exist</p>

    <h4>Source Code:</h4>

    <p><a href="#">Cache/Backend.php:25-31</a></p>


</div>
</div>


<div class="size-wrap footer-wrap">

    <table class="footer-links">
        <tr>
            <td>
                <div class="footer-links-title">Main</div>
                <ul class="footer-links-list unstyled">
                    <li><a href="#" class="link-black">Download</a></li>
                    <li><a href="#" class="link-black">Documentation</a></li>
                    <li><a href="#" class="link-black">Blog</a></li>
                </ul>
            </td>
            <td>
                <div class="footer-links-title">Services</div>
                <ul class="footer-links-list unstyled">
                    <li><a href="#" class="link-black">Consulting</a></li>
                    <li><a href="#" class="link-black">Hosting</a></li>
                </ul>
            </td>
            <td>
                <div class="footer-links-title">Support</div>
                <ul class="footer-links-list unstyled">
                    <li><a href="#" class="link-black">Forum/Community</a></li>
                    <li><a href="#" class="link-black">Stack Overflow</a></li>
                    <li><a href="#" class="link-black">Issue Tracker</a></li>
                </ul>
            </td>
            <td>
                <div class="footer-links-title">Get Involved</div>
                <ul class="footer-links-list unstyled">
                    <li><a href="#" class="link-black">Team</a></li>
                    <li><a href="#" class="link-black">About</a></li>
                    <li><a href="#" class="link-black">Roadmap</a></li>
                </ul>
            </td>
        </tr>
    </table>

    <div class="donate-wrap">
        Donate to Phalcon: <a href="#" class="button button-small orange">Flattr</a> or <a href="#" class="button button-small orange">via PayPal</a>
    </div>

    <div class="social-links">
        <a href="#" class="social-link tw">Twitter</a>
        <a href="#" class="social-link fb">Facebook</a>
        <a href="#" class="social-link gp">Google Plus</a>
        <a href="#" class="social-link vm">Vimeo</a>
    </div>

</div>


</div>
</body>
</html>
