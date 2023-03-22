<?php 
namespace App\Routing;

class URL {
	private ?Path $path = null;
	private string $host = "";
	private string $scheme = "";
	private array $query = [];

	public function __construct(string $uri) {
		$this->setURI($uri);
	}

	public function setURI(string $uri) {
		$url = parse_url($uri);
		
		if(isset($url['query'])) {
			parse_str($url['query'], $this->query);
		}

		if(isset($url['host'])) {
			$this->host = $url['host'];
		}

		if(isset($url['scheme'])) {
			$this->scheme = $url['scheme'];
		}

		$this->path = new Path($url['path']);
	}

    public function getPath() {
        return $this->path;
    }

    public function setPath(Path $path) {
        $this->path = $path;
        return $this;
    }

    public function getQuery() {
        return $this->query;
    }

    public function setQuery(array $query) {
        $this->query = $query;
        return $this;
    }

    public function getHost() {
		return $this->host;
    }
	
    public function setHost(string $host) {
		$this->host = $host;
        return $this;
    }
	
    public function getScheme() {
		return $this->scheme;
    }
	
    public function setScheme(string $scheme) {
		$this->scheme = $scheme;
        return $this;
    }

	public function __toString() {
		$uri = "";

		if($this->scheme != "") {
			$uri .= $this->scheme."://";
		}

		$uri .= $this->host . $this->path;

		if(!empty($this->query)) {
			$uri .= "?";
		}

		foreach($this->query as $query => $value) {
			$uri .= "$query=$value";
		}

		return $uri;
	}
}