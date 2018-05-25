<?php
// Defino un nombre único para el directorio de trabajo y lo creo
$directorio = '/tmp/talento_temporales/' . hash('sha256', rand() ) . '/';
mkdir( $directorio, 0777, true );

// Copio el zip
$zip_temporal = $directorio . 'Plantilla.pptx';
copy( 'Plantilla.pptx', $zip_temporal );

// Abro el zip
$zip = new ZipArchive;
$res = $zip->open( $zip_temporal );
if ($res === TRUE) {
       // Obtengo el slide
       $zip->extractTo( $directorio, array( 'ppt/slides/slide1.xml' ) );
       $slide1 = file_get_contents( $directorio . 'ppt/slides/slide1.xml' );
       $slide1 = str_replace( 'Nombre Completo: ', 'Nombre Completo: Juan David Botero', $slide1 );
       $zip->addFromString('ppt/slides/slide1.xml', $slide1 );
       $zip->close();

} else {
       echo 'failed';
}

// Entrego el archivo
header('Content-Description: File Transfer');
header('Content-type: application/vnd.openxmlformats-officedocument.presentationml.presentation' );
header('Content-Disposition: attachment; filename=""Ficha de Colaborador.pptx""');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize( $zip_temporal ) );
readfile( $zip_temporal );

// Elimino el directorio temporal
rmdir( $directorio );
?>
