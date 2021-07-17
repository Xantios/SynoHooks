## SynoHooks

Just a tiny container to setup a "SMS Driver" for Synology and bridge it to Discord
because WHO uses E-Mail / SMS ?

## Sweet! How?

Run this container with 2 env variables

`DISCORD_URL` the entire URL for the Discord hook
`TOKEN` A PSK to use

- Log in to DSM, go to control panel, Notifications
- Check "Enable SMS notifications"
- Click the "Add SMS Service provider" button

In the wizard that opens up, give the beast a name and the FULL url of where this container is running
`http://examle.tld:42069/` and set method to `GET`

create a key called `phony` and a key called `msg` with a default value of `hello world`

Bind the `Phonenumber` value to `phony` field we created (we dont care, we're using webhooks now!)
Bind the `msg` value to the message body
Bind the `token` value to the token you entered in the environment variable
