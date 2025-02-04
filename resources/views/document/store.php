<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ONLYOFFICE Document Editor Code Sample</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>body {height: 100vh; margin: 0}</style>
    <script src="http://localhost:8080/web-apps/apps/api/documents/api.js"></script>
</head>
<body>
<div id="placeholder"></div>
<script>
    const config = {
        document: {
            fileType: "docx"
        },
        documentType: "word",
        editorConfig: {
            customization: {
                anonymous: {
                    request: false,
                    label: "Guest"
                },
                integrationMode: "embed"
            }
        },
        height: "700px",
        width: "100%"
    }
    const editor = new DocsAPI.DocEditor("placeholder", config)
</script>
</body>
</html>