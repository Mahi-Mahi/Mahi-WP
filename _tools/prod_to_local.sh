echo "PROD TO LOCAL"

source .env

ssh $PROD_SSH "mysqldump --user=$PROD_DB_USER --password=$PROD_DB_PWD  $PROD_DB > $PROD_TMP/$PROD_DB.sql "
ssh $PROD_SSH "gzip -f $PROD_TMP/$PROD_DB.sql "
scp $PROD_SSH:$PROD_TMP/$PROD_DB.sql.gz $LOCAL_TMP/
gunzip -f $LOCAL_TMP/$PROD_DB.sql.gz
mysql -u $LOCAL_DB_USER --password=$_LOCAL_DB_PWD $LOCAL_DB <$LOCAL_TMP/$PROD_DB.sql
rsync -avz -e ssh $PROD_SSH:$PROD_DOCROOT/wp-content/uploads/ $LOCAL_DOCROOT/wp-content/uploads
