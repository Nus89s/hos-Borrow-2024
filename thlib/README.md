Thai Words Split Lib
==========

Lib ตัดคำภาษาไทย สำหรับ PHP ทดลองใช้กับ MPDF


การใช้งาน:
```php
    $words = $segment->get_segment_array($text);
    $text = implode("|",$words);
```


Config: 
```php
    $mpdf->useDictionaryLBR = false;
```

Edit Mpdf.php ค้นหา `3) Break at SPACE`:
```php
    if ($prevchar == '|') {
        $breakfound = [$contentctr, $charctr, $cutcontentctr, $cutcharctr, 'discard'];
    }
```

Edit Mpdf.php ค้นหา `// Selected OBJECTS are moved forward to next line, unless they come before a space or U+200B (type='discard')`:
```php
    /* -- END OTL -- */
    $currContent = str_replace('|','',$currContent); 
```

Edit Mpdf.php ค้นหา `// another character will fit, so add it on`:
```php
    $currContent = str_replace('|','',$currContent); 
    unset($content);
    unset($contentB);
```

Credits
- [Pongsathon Janyoi](https://medium.com/@pongsathon.janyoi?source=post_page-----bb66ca383b75--------------------------------)

