<?

$navbar->AddOption('Generate','main:doc:generate');

$MIOLO->GetUI();

include_once($MIOLO->GetModulePath('common','etc/documentation.class'));

global $SOURCE_EXTENSIONS, $result_content;

$SOURCE_EXTENSIONS = array('.inc','.class');

function ProcessDirectory($dir)
{   global $SOURCE_EXTENSIONS, $result_content;
    
    $result_content .= "Processing dir: $dir<br>";
    
    $fp = opendir($dir);
    
    if ( $fp )
    {
        while ( $ent = readdir($fp) )
        {
            // ignore all entries starting with dot as well as CVS directories
            if ( ( substr($ent,0,1) == '.' ) || 
                 ( $ent == 'CVS' )
               )
            {
                continue;
            }
            
            $path = $dir.'/'.$ent;
            
            if ( is_dir($path) )
            {
                ProcessDirectory($path);
            } 
            else if ( in_array(strrchr($path,'.'),$SOURCE_EXTENSIONS) )
            {
                $doc = new SourceDocumentation($path);
                $doc->Update(true);
            }
        }
        
        closedir($fp);
    }
}

$business = $MIOLO->GetBusiness('common','documentation');

$business->ClearAll();


$result_content .= "<strong>Processing: {$MIOLOCONF['home']['miolo']}</strong> <br>";
ProcessDirectory($MIOLOCONF['home']['miolo']);

$result_content .= "<br><strong>Processing: {$MIOLOCONF['home']['modules']}/common</strong> <br>";
ProcessDirectory($MIOLOCONF['home']['modules'].'/common');
// ProcessDirectory($MIOLOCONF['home']['html'].'/modules');

$theme->SetContent($result_content);

?>
