function render_md_math() {
    // Force mathjax rendering
    MathJax.typeset();

    // Convert markdown class elements to html 
    let converter = new showdown.Converter({ tables: 'true' });

    $('.md').each(function () {
        let text = $(this).html(),
            html = converter.makeHtml(text);
        $(this).html(html)
    })

    // Force syntax highlighting
    document.querySelectorAll('pre code').forEach((block) => {
        hljs.highlightBlock(block);
    });
}