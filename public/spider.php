<?php 

$url = $_POST['url'];
if(!empty($url)){
    $spider = new Spider($url);
    $urls = $spider->getUrls();
}

class Spider {
    
    protected $_curl;
    protected $_baseUrlParts = array();
    protected $_urls = array();
    
    public function __construct($url)
    {
        $this->_curl = curl_init();
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, 1);
        
        $this->_setBaseUrl($url);
        $this->_scanPageAtUrl($url);
        
        curl_close($this->_curl); 
    }
    
    public function getUrls()
    {
        return $this->_urls;
    }
    
    protected function _scanPageAtUrl($url)
    {
        set_time_limit(15);
        
        $url = $this->_parseUrl($url, $mailto);
        if($url && !in_array($url, $this->_urls)){
            
            if($mailto){
                $this->_urls[] = $url;
            }else{
            
                // download page
                curl_setopt($this->_curl, CURLOPT_URL, $url);
                $html = curl_exec($this->_curl);
                
                // scan page for links
                if($html && preg_match_all('/<a[^>]+href="([^"]+)"/i', $html, $matches)){
                    
                    $this->_urls[] = $url;
                    
                    foreach($matches[1] as $match){
                        $this->_scanPageAtUrl($match);
                    }
                }
            }
        }
    }
    
    protected function _parseUrl($url, &$mailto = false)
    {
        $parts = parse_url($url);
     
        if((
            isset($parts['scheme']) && 
            $parts['scheme'] != 'http' &&
            !$mailto = ($parts['scheme'] == 'mailto' && isset($parts['path']))
        ) || (
            isset($parts['host']) &&
            $parts['host'] != $this->_baseUrlParts['host']
        )){
            return false;
        }
        
        if($mailto){
            $out = $parts['scheme'] . ':' . $parts['path'];
        }else{
            $parts += $this->_baseUrlParts;
            $out = $parts['scheme'] . '://' . $parts['host'] . '/';
            if(isset($parts['path'])){
                $out .= $parts['path'];
            }
        }
        
        if(isset($parts['query'])){
            $out .= '?' . $parts['query'];
        }
        
        return $out;
    }
    
    protected function _setBaseUrl($url)
    {
        $parts = parse_url($url);
        
        unset($parts['path']);
        unset($parts['query']);
        unset($parts['fragment']);
        
        $this->_baseUrlParts = $parts;
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title>Spider</title>
</head>

<body>
<form method="post">
<label for="url">URL to crawl</label>
<input type="text" name="url" id="url" value="<?php echo htmlspecialchars($url); ?>"/>
<input type="submit" name="submit" value="Go"/>
</form>
<?php if(isset($urls)): ?>
<ul>
<?php foreach($urls as $url): ?>
<li><a href="<?php echo $url; ?>"><?php echo $url; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</body>
</html>