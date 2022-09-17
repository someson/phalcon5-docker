<p class="lead mt-5 text-success"><strong>Project structure using Phalcon PHP Framework {{ frameworkVersion }}</strong></p>
<table class="table table-bordered table-hover table-sm">
  <tbody>
  <tr>
    <td>Webserver</td>
    <td>{{ webServerVersion }}</td>
  </tr>
  <tr>
    <td>php</td>
    <td><a href="/__info.php" title="phpinfo()">{{ phpVersion }}</a></td>
  </tr>
  </tbody>
</table>

<p>Flashmessage Direct:</p>
{{ content() }}

<p>Flashmessage Session:</p>
{{ flashSession.output() }}
