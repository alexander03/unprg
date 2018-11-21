<?php
	require_once("./src/autoload.php");
	
	$company = new \Sunat\Sunat( true, true );

	$ruc = $_GET['ruc'];
	
	$search1 = $company->search($ruc);

	if($search1->success === true)
	{
		$parametros = array(
			'razon_social'=> $search1->result->razon_social,
			'direccion'=> $search1->result->direccion
		);
	} else {
		$parametros = array(
			'razon_social'=> null,
			'direccion'=> null
		);
	}
	echo json_encode($parametros);
	
?>
