<?php
/**
* http://php.net/manual/es/book.zip.php
* lo archivos pptx pueden ser tratados
*/

// Defino un nombre único para el directorio de trabajo y lo creo
$directorio = '/tmp/temporales/' . hash('sha256', rand() ) . '/';
mkdir( $directorio, 0777, true );

// Copio el zip
$zip_temporal = $directorio . 'Plantilla.pptx';
copy( 'Plantilla.pptx', $zip_temporal );

// Abro el zip
$zip = new ZipArchive;
$res = $zip->open( $zip_temporal );
if ($res === TRUE) {
       // Obtengo el slide
       $zip->extractTo( $directorio, array( 'ppt/slides/slide1.xml', 'ppt/media/mi_imagen.png' ) );
       
       //tambien se puede hacer el remplazo de imagenes, 
       //para eso debe tener en cuenta el nombre y tipo del archivo que se desea rempazar
       //$fichero = 'ppt/media/mi_imagen.png';
       //unlink($directorio .$fichero );
       //copy('la nueva imagen', $directorio .$fichero);
       //guardo el nuevo archivo en el zip
       //$zip->addFile($directorio .$fichero, 'ppt/media/image6.png' );
       
       //cargo la informacion del archivo que deseo modificar
       $slide1 = file_get_contents( $directorio . 'ppt/slides/slide1.xml' );
       //busco el texto que y remplazo
       $slide1 = str_replace( 'Nombre Completo: ', 'Mi nombre completo', $slide1 );
       
       //luego actualizo el zip con el nuevo contenido 
       $zip->addFromString('ppt/slides/slide1.xml', $slide1 );
       
       //cierro el archivo
       $zip->close();
       

} else {
       echo 'failed';
}

// Entrego el archivo
header('Content-Description: File Transfer');
header('Content-type: application/vnd.openxmlformats-officedocument.presentationml.presentation' );
header('Content-Disposition: attachment; filename=""ficha.pptx""');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize( $zip_temporal ) );
readfile( $zip_temporal );

// Elimino el directorio temporal
rmdir( $directorio );
?>
