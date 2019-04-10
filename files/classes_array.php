<?php
    $fileName = FILENAME_RDF;
    $classes = CLASSES;
    $ontology = ONTOLOGY;

    $cidoc = new EasyRdf_graph();
    $cidoc->parseFile(__DIR__ . "/" . $fileName);
    $cidoc_resources = $cidoc->resources(); 

    $class_array = Array();

    foreach ($cidoc_resources as $key => $val) {
        if ($val->type() == $classes){
            $class = str_replace('_', ' ', $val->localName());
            
            array_push($class_array, Array
            (
                "o:label" => $class,
                "o:comment" => $class,
                "o:local_name" => strtolower($class),
                "o:vocabulary" => $ontology,
                "term" => $ontology . ":". strtolower($class),
            ));
        }
    }
?>