<!DOCTYPE html>
<html lang="{{ site.locale }}" class="h-100">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes, minimum-scale=1">
{{ get_title() }}
<link rel="shortcut icon" href="/assets/favicon.ico">
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
    <span class="text-muted">
      <a href="https://github.com/someson/phalcon5-docker">{{ __('global:footer') }}</a>
    </span>
  </div>
</footer>
{% if assets.has('footerJs') %}{{ assets.outputJs('footerJs') }}{% endif %}
{% if assets.has('footerMainJs') %}{{ assets.outputJs('footerMainJs') }}{% endif %}
</body>
</html>
