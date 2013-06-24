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

<?php if($classData['description']): ?>
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


<h2>Methods summary</h2>

<table class="api-methods-table">

    <tr>
        <td class="method"><span class="method-type public" data-tooltip="public"></span> <a href="#">__construct</a></td>
        <td class="description">Phalcon\Cache\Backend constructor</td>
    </tr>
    <tr>
        <td class="method"><span class="method-type public" data-tooltip="public"></span><a href="#">start</a></td>
        <td class="description">Starts a cache. The $keyname allows to identify the created fragment</td>
    </tr>
    <tr>
        <td class="method"><span class="method-type public" data-tooltip="public"></span><a href="#">stop</a></td>
        <td class="description">Stops the frontend without store any cached content</td>
    </tr>
    <tr>
        <td class="method"><span class="method-type public" data-tooltip="public"></span><a href="#">getFrontend</a></td>
        <td class="description">Returns front-end instance adapter related to the back-end</td>
    </tr>
    <tr>
        <td class="method"><span class="method-type public" data-tooltip="public"></span><a href="#">getOptions</a></td>
        <td class="description">Returns the backend options</td>
    </tr>
    <tr>
        <td class="method"><span class="method-type public" data-tooltip="public"></span><a href="#">isFresh</a></td>
        <td class="description">Checks whether the last cache is fresh or cached</td>
    </tr>
    <tr>
        <td class="method"><span class="method-type public" data-tooltip="public"></span><a href="#">isStarted</a></td>
        <td class="description">Checks whether the cache has starting buffering or not</td>
    </tr>
    <tr>
        <td class="method"><span class="method-type public" data-tooltip="public"></span><a href="#">setLastKey</a></td>
        <td class="description">Sets the last key used in the cache</td>
    </tr>
    <tr>
        <td class="method"><span class="method-type public" data-tooltip="public"></span><a href="#">getLastKey</a></td>
        <td class="description">Gets the last key stored by the cache</td>
    </tr>

</table>


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
