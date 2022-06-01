<link rel="stylesheet" href="//unpkg.com/flatpickr/dist/flatpickr.min.css">
<!-- カスタムテーマ -->
<link rel="stylesheet" href="//unpkg.com/flatpick/dist/themes/airbnb.css">
<script src="//unpkg.com/flatpickr"></script>
<!-- 日本語の言語ファイル -->
<script src="//unpkg.com/flatpickr/dist/l10n/ja.js"></script>

<input class="input" id="myCal" type="text" />

<script>
flatpickr("#myCal", {
  // 日本語化
  locale: "ja"
});
</script>