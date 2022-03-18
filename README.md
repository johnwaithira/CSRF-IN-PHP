# CSRF-IN-PHP ( Cross-Site Request Forgery )

- ``CSRF`` stands for cross-site request forgery. It’s a kind of attack in which a hacker forces you to execute an action against a website where you’re currently logged in.


For example, you visit the ``malicious-site.com`` that has a hidden form. And that form submits on page load to ``yourbank.com/transfer-fund form``.

Because you’re currently logged in to the ``yourbank.com``, the request silently transfers a fund out of your bank account.

If ``yourbank.com/transfer-fund`` implements the ``CSRF`` correctly, it generates a one-time token and inserts the token into the fund transfer form like this:

``` html
<input type="hidden" 
       name="token"        value="b3f44c1eb885409c222fdb78c125f5e7050ce4f3d15e8b15ffe51678dd3a33d3a18dd3">


```
When the ```malicious-site.com``` submits the form, the ``yourbank.com/transfer-fund form`` compares the token with the one on the *yourbank.com‘s* server.

If the token doesn’t exist in the submitted data or it doesn’t match with the token on the server, the fund transfer form will reject the submission and return an error.

When the ``malicious-site.com ``tries to submit the form, the token is likely not available or won’t match.

