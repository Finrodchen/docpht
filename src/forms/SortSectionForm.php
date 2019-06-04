<?php declare(strict_types=1);

/**
 * This file is part of the DocPHT project.
 * 
 * @author Valentino Pesce
 * @copyright (c) Valentino Pesce <valentino@iltuobrand.it>
 * @copyright (c) Craig Crosby <creecros@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../lib/functions.php';
require __DIR__.'/../lib/Model.php';

use Nette\Forms\Form;

$db = new DocData;
$docBuilder = new DocBuilder();

$aPath = $_SESSION['update_path'];
$id = $db->getId($aPath);

$data = $db->getPageData($id);

if(isset($_GET['o']) && isset($_GET['n'])) {
    $rowIndex = intval($_GET['o']);
    $newIndex = intval($_GET['n']);
}

if (is_integer($newIndex) && $newIndex < count($data)) {

    $moveData = $data[$rowIndex];
    array_splice($data, $rowIndex, 1);
    array_splice($data, $newIndex, 0, array($moveData));
    $db->putPageData($id, $data);

    if (!empty($data)) {
        $docBuilder->buildPhpPage($id);
        header('location:index.php?p='.$db->getFilename($id).'&f='.$db->getTopic($id));
		exit;
    }
} else {
    header('location:index.php?p='.$db->getFilename($id).'&f='.$db->getTopic($id));
    exit;
}