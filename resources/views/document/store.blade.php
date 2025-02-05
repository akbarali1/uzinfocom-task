<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ONLYOFFICE Document Editor Code Sample</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>body {height: 100vh; margin: 0}</style>
    <script src="https://bug-free-disco-6rxv7qxjvpqhxq5r-8080.app.github.dev/web-apps/apps/api/documents/api.js"></script>
</head>
<body>
<div id="placeholder"></div>
<script>
//    const docEditor = new DocsAPI.DocEditor("placeholder", {
//     document: {
//         fileType: "docx",
//         // key: "Khirz6zTPdfd7",
//         title: "new.docx",
//         url: "https://uzinfocom.akbarali.uz/files/new.docx",
//     },
//     documentType: "word",
//     token: "{{ $item->token }}",
//     });

    // OnlyOffice editorni sozlash
    var config = {
            document: {
                fileType: "docx",
                key: "Khirz6zTPdfd7",  // Hujjatning kaliti
                title: "example.docx",  // Hujjat nomi
                url: "https://uzinfocom.akbarali.uz/files/new.docx"  // Hujjatning URL manzili
            },
            editorConfig: {
                mode: "edit",  // Muharrir rejimi (edit, view)
                callbackUrl: "http://yourserver.com/callback",  // Hujjatda o'zgarishlar bo'lganida chaqiriladigan URL
                user: {
                    id: {{auth()->user()->id}},  // Foydalanuvchi ID
                    name: "{{auth()->user()->name}}"  // Foydalanuvchi nomi
                }
            },
            jwt: "{{ $item->token }}"  // JWT tokenni yuborish
        };

        // OnlyOffice Editorni yuklash va ishga tushirish
        var docEditor = new DocsAPI.DocEditor("placeholder", config);
</script>
</body>
</html>