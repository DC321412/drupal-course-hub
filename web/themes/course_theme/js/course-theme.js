/**
 * @file
 * Course Theme behaviors.
 */
((Drupal, once) => {
  Drupal.behaviors.courseThemeBackToTop = {
    attach(context) {
      once('back-to-top', 'body', context).forEach((body) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'back-to-top';
        button.textContent = Drupal.t('Back to top');
        button.hidden = true;

        button.addEventListener('click', () => {
          window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        window.addEventListener('scroll', () => {
          button.hidden = window.scrollY < 300;
        });

        body.appendChild(button);
      });
    },
  };
})(Drupal, once);
