<?php
include '../includes/config.php';
$last_modified = timestamp(__FILE__);
header('Content-type: image/svg+xml');
header('Last-Modified: '. $last_modified);
if (filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE') === $last_modified)
	header('HTTP/1.1 304 Not Modified');
?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40.0002 32.449601"><path fill="<?=$color ? hsla($color) : '#007BFF'?>" d="m1.4977-6.5613e-7c-0.78294 0-1.4977 0.80919-1.4977 1.4977v29.501c0 0.80762 0.69171 1.4518 1.5601 1.4509l36.88-0.0468c0.86835-0.00099 1.5601-0.64324 1.5601-1.4508v-27.036c0-0.80762-0.69171-1.468-1.5601-1.4665l-23.401 0.0468-4.992-2.4961h-8.5492zm3.5569 4.9922h9.9844v4.9922l-8.3151 6.2402 1.6537 1.248 6.6615-4.9922 9.9844-7.4883h12.48l-14.977 12.48 14.977 12.48h-12.48l-9.9844-7.4883-6.6615-4.9922-1.6537 1.248 8.3151 6.2402v4.9922h-9.9844v-9.9844l1.6693-1.248-1.6693-1.248 1.6693-1.248-1.6693-1.248v-9.9848z"/></svg>
