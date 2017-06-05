<?php

$conexao = new mysqli("localhost", "root", "", "rest");
$conexao->query("SET NAMES 'utf-8'");
$conexao->query("SET character_set_connction=utf-8");
$conexao->query("SET character_set_client=utf-8");
$conexao->query("SET character_set_results=utf-8");