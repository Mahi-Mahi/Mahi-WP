echo "STAGING TO LOCAL"

source .env

ssh $STAGING_SSH "mysqldump --user=$STAGING_DB_USER --password=$STAGING_DB_PWD  $STAGING_DB > $STAGING_TMP/$STAGING_DB.sql "
ssh $STAGING_SSH "gzip -f $STAGING_TMP/$STAGING_DB.sql "
scp $STAGING_SSH:$STAGING_TMP/$STAGING_DB.sql.gz $LOCAL_TMP/
gunzip -f $LOCAL_TMP/$STAGING_DB.sql.gz
mysql -u $LOCAL_DB_USER --password=$_LOCAL_DB_PWD $LOCAL_DB <$LOCAL_TMP/$STAGING_DB.sql
rsync -avz -e ssh $STAGING_SSH:$STAGING_DOCROOT/wp-content/uploads/ $LOCAL_DOCROOT/wp-content/uploads
