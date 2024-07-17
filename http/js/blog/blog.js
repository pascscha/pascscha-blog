const converter = new showdown.Converter();

function scrollToSection(event) {
    event.preventDefault();
    const targetId = event.target.getAttribute('href').slice(1);
    const targetElement = document.getElementById(targetId);
    const headerHeight = document.querySelector('header').offsetHeight;
    const extraOffset = 20; // Additional pixels for breathing room
    const totalOffset = headerHeight + extraOffset;

    window.scrollTo({
        top: targetElement.offsetTop - totalOffset,
        behavior: 'smooth'
    });
}

function generateTOC() {
    const toc = document.getElementById('toc');
    const headings = document.querySelectorAll('#rendered-content h1, #rendered-content h2, #rendered-content h3');
    const tocList = document.createElement('ul');

    headings.forEach((heading, index) => {
        const listItem = document.createElement('li');
        const link = document.createElement('a');

        // Create an id for the heading if it doesn't have one
        if (!heading.id) {
            heading.id = `heading-${index}`;
        }

        link.textContent = heading.textContent;
        link.href = `#${heading.id}`;
        link.addEventListener('click', scrollToSection);

        listItem.appendChild(link);
        tocList.appendChild(listItem);

        // Indent sub-headings
        if (heading.tagName === 'H2') {
            listItem.style.marginLeft = '20px';
        } else if (heading.tagName === 'H3') {
            listItem.style.marginLeft = '40px';
        }
    });

    toc.appendChild(tocList);
}


function highlightTOC() {
    const headings = Array.from(document.querySelectorAll('#rendered-content h1, #rendered-content h2, #rendered-content h3'));
    const tocLinks = document.querySelectorAll('#toc a');
    const headerHeight = document.querySelector('header').offsetHeight;

    // Sort headings by their distance from the top of the viewport
    headings.sort((a, b) => {
        return Math.abs(a.getBoundingClientRect().top - headerHeight) -
            Math.abs(b.getBoundingClientRect().top - headerHeight);
    });

    // The first heading in the sorted array is the closest to the top
    const closestHeading = headings[0];

    // Remove 'active' class from all links
    tocLinks.forEach(link => link.classList.remove('active'));

    // Add 'active' class to the link corresponding to the closest heading
    tocLinks.forEach(link => {
        if (link.getAttribute('href') === `#${closestHeading.id}`) {
            link.classList.add('active');
        }
    });
}

var afterRenderHooks = []

document.addEventListener('DOMContentLoaded', function () {
    const renderedContentDiv = document.getElementById('rendered-content');
    const readTimeSpan = document.getElementById('read-time');
    const tocToggle = document.getElementById('toc-toggle');
    const sidebar = document.getElementById('sidebar');
    const toc = document.getElementById('toc');

    function toggleTOC() {
        toc.classList.toggle('collapsed');
        sidebar.classList.toggle('collapsed');

        if (toc.classList.contains('collapsed')) {
            tocToggle.textContent = '☰';
        } else {
            tocToggle.textContent = '✕ Hide Table of Contents';
        }
    }

    tocToggle.addEventListener('click', toggleTOC);

    // Initial state (collapsed)
    // toggleTOC();

    // Fetch and render the Markdown content
    fetch('index.md')
        .then(response => response.text())
        .then(markdown => {
            const convertedHTML = converter.makeHtml(markdown);
            renderedContentDiv.innerHTML = convertedHTML;

            // Make images look nicer
            const images = renderedContentDiv.querySelectorAll('img');
            images.forEach(img => {
                const container = document.createElement('div');
                container.className = 'image-container';

                const description = document.createElement('div');
                description.className = 'image-description';
                description.innerHTML = img.alt;

                img.parentNode.insertBefore(container, img);
                container.appendChild(img);
                container.appendChild(description);
            });

            // Open links in new tab
            const links = renderedContentDiv.getElementsByTagName('a');
            for (let link of links) {
                link.setAttribute('target', '_blank');
                link.setAttribute('rel', 'noopener noreferrer');
            }

            // Calculate read time
            const wordCount = markdown.trim().split(/\s+/).length;
            const readTimeMinutes = Math.ceil(wordCount / 200);
            readTimeSpan.textContent = `${readTimeMinutes} min read`;

            // Generate table of contents
            generateTOC();


            // Highlight TOC on scroll
            highlightTOC();
            window.addEventListener('scroll', highlightTOC);

            // Highlight Code
            hljs.highlightAll();

            for (var i = 0; i < afterRenderHooks.length; i++) {
                afterRenderHooks[i]();
            }
        })
        .catch(error => console.error('Error:', error));

});