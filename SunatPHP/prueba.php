<?php
	require_once("./src/autoload.php");
	
	$company = new \Sunat\Sunat( true, true );
	$ruc = "10737004509";
	
	$search1 = $company->search( $ruc );

	if( $search1->success === true )
	{
		echo "Empresa: " . $search1->result->razon_social;
		echo "Empresa: " . $search1->result->direccion;
	}
	
?>
