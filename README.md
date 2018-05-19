# ImagePlaceholder for a-blog cms

a-blog cms Ver.2.8.0より拡張アプリ「ImagePlaceholder」を利用できるようになります。

## ダウンロード
[ImagePlaceholder for a-blog cms](https://github.com/appleple/acms-image-placeholder/raw/master/build/ImagePlaceholder.zip)


## 使い方
まず、SVG画像はエントリーなどの保存時に生成されますので、上記の設定が完了後、エントリーを再保存して下さい。その後、SVGプレイスホルダーの出すには校正オプションを使って出力します。

### 単色プレイスホルダーの場合

```
<img src="{path}[fillCollorImage]" alt="{alt}" width="{x}" height="{y}"/>
```

### シルエットプレイスホルダーの場合
```
<img src="{path}[dataUrlSvg]" alt="{alt}" width="{x}" height="{y}"/>
```

ただ、このままだと画像がプレイスホルダー画像に差し代わるだけで、プレイスホルダーとして動きません。 そこで、最初はSVGプレイスホルダー画像を表示しておき、JavaScriptを使って画像がロードされたタイミングで、本来の画像に差し替える処理を行います。

```
<script>
  ACMS.Ready(function(){
    $('.js-lazy-load').each(function(){
      var $self = $(this);
      var url = $self.data('url');
      var imageObj = new Image();
      imageObj.item = $self;
      imageObj.source = url;
      imageObj.onload = function(){
        var self = this;
        setTimeout(function(){
          self.item.attr('src', self.source);
        }, 500 + 3000 * Math.random());
      };
      imageObj.src = url;
    });
  });
</script>
<img class="js-lazy-load"
     src="{path}[dataUrlSvg]"
     alt="{alt}"
     width="{x}"
     height="{y}"
     data-url="%{HTTP_ROOT}{path}"
     data-placeholder="{path}[dataUrlSvg]"
>
```