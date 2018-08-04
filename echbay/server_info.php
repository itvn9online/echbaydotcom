<?php



//
//phpinfo();

//
//print_r( $_SERVER );

//
echo '<table border="0" cellpadding="0" cellspacing="0" class="eb-public-table">';

//
foreach ( $_SERVER as $k => $v ) {
	if ($k == 'HTTP_COOKIE') {
		$v = '<strong>F12</strong>';
	}
	
	//
	echo '
	<tr>
		<td class="t">' . $k . '</td>
		<td class="i">' . $v . '</td>
	</tr>';
}


//
global $client_ip;

$other = array(
	'DB NAME' => DB_NAME,
	'Your IP' => $client_ip,
	'Date time' => date( 'r', date_time ),
	'Server time' => date( 'r', $_SERVER['REQUEST_TIME'] )
);

foreach ( $other as $k => $v ) {
	echo '
	<tr>
		<td class="t">' . str_replace( ' ', '_', strtoupper( $k ) ) . '</td>
		<td class="i">' . $v . '</td>
	</tr>';
}



//
echo '</table>';


