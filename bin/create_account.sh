#!/bin/bash
# Run every minute

MAIL_FROM='user@host'
MAIL_FROM_NAME='The Mana World server'
MAIL_SUBJECT='The Mana World account registration'


SQL_HOST='localhost'
SQL_USER='user'
SQL_PASSWORD='password'
SQL_DATABASE='db'
SQL_TABLE='table'

LADMIN_DIR='/path/to/tmwserver/login/'
LADMIN_BIN='tmwa-admin'

### end configuration


mail_command()
{
# no arguments; takes a message on stdin
    /usr/sbin/sendmail -t
}

die()
{
    echo "$@"
    exit 1
}

one_word()
{
    test $# -eq 1 || die "Error: evil spaces!"
}

one_word $SQL_HOST
one_word $SQL_USER
one_word $SQL_PASSWORD
one_word $SQL_DATABASE
one_word $SQL_TABLE

send_email()
{
# Note: USERNAME and EMAIL are captured
mail_command << __EOF__
From: $MAIL_FROM_NAME <$MAIL_FROM>
To: $USERNAME <$EMAIL>
Subject: $MAIL_SUBJECT

Hello $USERNAME,

$@
__EOF__
}

do_mysql()
{
    # SQL statements on stdin
    mysql --host="$SQL_HOST" --user="$SQL_USER" --password="$SQL_PASSWORD" "$SQL_DATABASE"
}

frob_accounts()
{
    IFS=$'\t'
    while read ID USERNAME PASSWORD EMAIL GENDER
    do
        if test "$GENDER" = '1'; then GENDER=M; fi
        if test "$GENDER" = '2'; then GENDER=F; fi

        RESULT=$(cd $LADMIN_DIR; $LADMIN_BIN <<< "create $USERNAME $GENDER $EMAIL $PASSWORD" 2>&1)
        echo RESULT: "$RESULT"
        if grep -q 'successfully created' <<< "$RESULT"
        then
            send_email 'Your account was created successfully. Have fun playing The Mana World!'
            do_mysql << __EOF__
update $SQL_TABLE set state = 1 where id = $ID
__EOF__
        else
            send_email $'Something went wrong when automatically creating your account.\nError message:' "$RESULT"
            do_mysql << __EOF__
update $SQL_TABLE set state = 2 where id = $ID
__EOF__
        fi
    done
}

do_mysql << __EOF__ | tail -n+2 | frob_accounts
select id, username, password, email, gender
from $SQL_TABLE
where state = 0
__EOF__

echo Everything is Ok.
