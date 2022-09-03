<!DOCTYPE html>
<html lang="{{ site.locale }}" class="h-100">
<head>
<meta charset="utf-8">
<title>{{ get_title() }}</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes, minimum-scale=1">
<link rel="shortcut icon" href="/assets/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous" />
{% if assets.has('headerCss') %}{{ assets.outputCss('headerCss') }}{% endif %}
</head>
<body class="d-flex flex-column h-100">
<main role="main" class="flex-shrink-0">
  <div class="container">
    {{ content() }}
  </div>
</main>
<footer class="footer mt-auto py-3">
  <div class="container">
    <p class="text-muted">
      <a href="https://github.com/someson/phalcon5-docker">https://github.com/someson/phalcon5-docker</a>
    </p>
  </div>
</footer>
{% if assets.has('footerJs') %}{{ assets.outputJs('footerJs') }}{% endif %}
{% if assets.has('footerMainJs') %}{{ assets.outputJs('footerMainJs') }}{% endif %}
</body>
</html>
