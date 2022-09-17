<!DOCTYPE html>
<html lang="{{ site.locale }}">
<head>
<meta charset="utf-8">
<title>{{ get_title() }}</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes, minimum-scale=1">
<meta name="description" content="{{ errCode }}">
<link rel="shortcut icon" href="/assets/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
{{ assets.outputCss() }}
</head>
<body class="text-center" style="margin-top:30px">
<div class="container">
  <h1><span class="badge text-bg-danger">{{ errCode }}</span></h1>

  {%- if config.debug and exceptionData is defined %}
  <div class="card bg-light mb-3 mt-3 text-center">
    <div class="card-header"><pre style="margin-bottom:auto;font-size:inherit">{{ exceptionData.class }}</pre></div>
    <div class="card-body">{{ exceptionData.message }}</div>
  </div>
  {% endif -%}

</div>
</body>
</html>
