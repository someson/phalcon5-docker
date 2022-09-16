<?php

namespace App\Shared;

use Phalcon\Support\Helper\Arr\Get;

class Debug extends \Phalcon\Support\Debug
{
    public function createTabHeaders(): string
    {
        $tabHeaders = <<<END
          <a class="nav-item nav-link active" id="tab-1" data-toggle="tab" href="#error-tabs-1" role="tab" aria-controls="error-tabs-1" aria-selected="true">Backtrace</a>
          <a class="nav-item nav-link" id="tab-2" data-toggle="tab" href="#error-tabs-2" role="tab" aria-controls="error-tabs-2" aria-selected="false">Request</a>
          <a class="nav-item nav-link" id="tab-3" data-toggle="tab" href="#error-tabs-3" role="tab" aria-controls="error-tabs-3" aria-selected="false">Server</a>
END;
        if (is_array($this->data)) {
            $tabHeaders .= '<a class="nav-item nav-link" id="tab-4" data-toggle="tab" href="#error-tabs-4" role="tab" aria-controls="error-tabs-4" aria-selected="false">Variables</a>';
        }
        return $tabHeaders;
    }

    public function createTabContent($backtrace): string
    {
        $getArray = new Get();
        $request = '';
        $blacklist = $getArray($this->blacklist, 'request', []);
        foreach ($_REQUEST as $keyRequest => $value) {
            if (! isset($blacklist[strtolower($keyRequest)])) {
                $request.= sprintf('<tr><td>%s</td><td>%s</td></tr>', $keyRequest, \is_array($value) ? print_r($value, true) : $value);
            }
        }

        $globalVars = '';
        $blacklist = $getArray($this->blacklist, 'server', []);
        foreach ($_SERVER as $keyServer => $value) {
            if (! isset($blacklist[strtolower($keyServer)])) {
                $globalVars.= sprintf('<tr><td>%s</td><td>%s</td></tr>', $keyServer, $this->getVarDump($value));
            }
        }

        $tabContent = <<<END
  <div class="tab-pane fade show active" id="error-tabs-1" role="tabpanel" aria-labelledby="tab-1">
    <table class="table table-borderless table-sm table-hover mt-3">
      {$backtrace}
    </table>
  </div>
  <div class="tab-pane fade" id="error-tabs-2" role="tabpanel" aria-labelledby="tab-2">
    <table class="table table-stripped table-bordered table-sm table-hover mt-3">
      <thead>
        <th scope="col">Key</th>
        <th scope="col">Value</th>
      </thead>
      <tbody>
        {$request}            
      </tbody>
    </table>          
  </div>
  <div class="tab-pane fade" id="error-tabs-3" role="tabpanel" aria-labelledby="tab-3">
    <table class="table table-stripped table-bordered table-sm table-hover mt-3">
      <thead>
        <th scope="col">Key</th>
        <th scope="col">Value</th>
      </thead>
      <tbody>
        {$globalVars}            
      </tbody>
    </table>
  </div>
END;
        if (is_array($this->data)) {
            $extra = '';
            foreach ($this->data as $keyVar => $dataVar) {
                $extra.= sprintf('<tr><td>%s</td><td>%s</td></tr>', $keyVar, $this->getVarDump($dataVar[0]));
            }
            $tabAdditional = <<<END
  <div class="tab-pane fade" id="error-tabs-4" role="tabpanel" aria-labelledby="tab-4">
    <table class="table table-stripped table-bordered table-sm table-hover mt-3">
      <thead>
        <th scope="col">Key</th>
        <th scope="col">Value</th>
      </thead>
      <tbody>
        {$extra}            
      </tbody>
    </table>
  </div>
END;
            $tabContent .= $tabAdditional;
        }
        return $tabContent;
    }

    public function onUncaughtException(\Throwable $exception): bool
    {
        $obLevel = ob_get_level();
        if ($obLevel > 0) {
            ob_end_clean();
        }
        if (self::$isActive) {
            echo $exception->getMessage();
            return true;
        }
        $className = $exception::class;
        $escapedMessage = $this->escapeString($exception->getMessage());
        $showBackTrace = $this->showBackTrace;

        $debugInfo = '';
        if ($showBackTrace) {
            $backtrace = 'No backtrace';
            $trace = $exception->getTrace() ?: [];
            foreach ($trace as $n => $traceItem) {
                $backtrace.= $this->showTraceItem($n, $traceItem);
            }
            $debugInfo = <<<END
      <nav>
        <div class="nav nav-tabs" id="debug-tab" role="tablist">
          {$this->createTabHeaders()}
        </div>
      </nav>
      <div class="tab-content" id="nav-tabContent">
        {$this->createTabContent($backtrace)}
      </div>
END;
        }

        $memoryUsage = memory_get_usage(true);
        echo <<<END
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>{$className}: {$escapedMessage}</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/assets/css/debug.css">
</head>
<body class="d-flex flex-column h-100">
  <main role="main" class="flex-shrink-0">
    <div class="container">
      <div class="alert alert-danger mt-3" role="alert">
        <h5 class="alert-heading">{$className}: {$escapedMessage}</h5>
        <p class="mb-0">{$exception->getFile()} ({$exception->getLine()})</p>
      </div>    
      {$debugInfo}
    </div>
  </main>
  <footer class="footer mt-auto py-3">
    <div class="container">
      <div class="row">
        <div class="col-sm-6"><span class="text-muted">{$this->getVersion()}</span></div>
        <div class="col-sm-6 text-right"><span class="text-muted">Memory usage: {$memoryUsage}</span></div>
      </div>
    </div>
  </footer>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script type="text/javascript" src="/assets/js/debug.js"></script>
</body>
</html>
END;
        self::$isActive = false;
        return true;
    }
}
