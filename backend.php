<?php

$curl = curl_init();

//o array com os dados que eu vou pegar no front depois atráves de um form

/*
"Username": "INT.DEV",
"Password": "MXM",
"EnvironmentName": "TESTE_MOBILE"

*/

if(isset($_POST['nomef']) || isset($_POST['senhaf']) || isset($_POST['ambientef']) ){
    $nome = strval($_POST['nomef']);
    $senha = strval($_POST['senhaf']);
    $ambiente = strval($_POST['ambientef']);

    $post = "{'AutheticationToken': {
        'Username': '$nome',
        'Password': '$senha',
        'EnvironmentName': '$ambiente'
 },
 'Data': {
     'CodigoProjeto': '0',
     'DescricaoProjeto': 'projeto teste',
     'Inativo': 'N'
 }}";
 
 
 $headers = [
     'Content-type: application/json',
     'Content-length:' . strlen($post),
     ];
}else{
    throw new Exception("nome ou senha inválida");
}


//configuração
curl_setopt_array($curl, [

    CURLOPT_URL => "https://arquitetura.mxm.com.br/teste_mobile/api/InterfacedoProjeto/Gravar",
    CURLOPT_SSL_VERIFYPEER => false, //como a conexão é do meu local eu desativei a verificação ssl 
    CURLOPT_CUSTOMREQUEST => 'POST', //metodo de requisição
    CURLOPT_POSTFIELDS => $post, //os campos que vão ser enviados na requisição
    CURLOPT_HTTPHEADER => $headers, //headers dizendo que tipo de conteudo eu to enviando
    CURLOPT_RETURNTRANSFER => true //true para retornar a transferência como uma string do valor de retorno de curl_exec() em vez de enviá-la diretamente.
    
]);

    $responsejSON = curl_exec($curl);
    $dadosObj = json_decode($responsejSON);

    if($dadosObj->Success == 1){

        $rotaSucesso = "sucesso.php?var=".strval($dadosObj->Messages[0]->Message);
        header('Location:'. $rotaSucesso);
        exit;

    }else{

        $rotaErro = "erro.php?var=".strval($dadosObj->Messages[0]->Message);
        header('Location:'.$rotaErro);
        exit;
        
    }

curl_close($curl);



