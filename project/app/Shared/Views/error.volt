<!DOCTYPE html>
<html lang="{{ site.locale }}">
<head>
<meta charset="utf-8">
<title>{{ errCode }} - {{ errMessage }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes, minimum-scale=1">
<meta name="description" content="{{ errCode }} - {{ errMessage }}">
<link rel="shortcut icon" href="/assets/favicon.ico">
{{ stylesheet_link('/assets/libs/bootstrap/css/bootstrap.min.css') }}
{{ assets.outputCss() }}
</head>
<body class="text-center" style="margin-top:30px">
<div class="container">
  <h1><span class="badge badge-secondary badge-danger">{{ errCode }}</span> {{ errMessage }}</h1>

  {%- if config.debug and exceptionData is defined %}
  <div class="card bg-light mb-3 mt-3 text-center">
    <div class="card-header">{{ exceptionData.class }}</div>
    <div class="card-body">{{ exceptionData.message }}</div>
  </div>
  {% endif -%}

  <p>{{ linkTo(site.staticUri, site.domain, false) }} &copy; {{ date('Y') }}</p>
</div>
</body>
</html>
