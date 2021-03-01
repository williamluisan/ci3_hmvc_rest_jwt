## Description ##
An example of Codeignter 3 app packed with
1. HMVC (Hierarchical Model View Controller)
2. Codeigniter REST
3. JWT for REST authentication

## Brief expalanation to try ##
- Login to get the access token. endpoin: `POST /auth/token`
<br/>&nbsp;&nbsp;&nbsp;&nbsp;id: __100__
<br/>&nbsp;&nbsp;&nbsp;&nbsp;key: __appkey100__
- Try to access it. endpoin: `POST /teachers`
- Request new access token. endpoin `POST /auth/token_refresh`

#### References ####
- Codeigniter 3: https://codeigniter.com 
- HMVC by __wiredesignz__: https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc
- REST Library by __chriskacerguis__: https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc
- JWT Library by __firebase__: https://github.com/firebase/php-jwt
