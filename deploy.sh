#!/usr/bin/bash
export $(cat .env) && \
cd ./src && \
echo "Deploying files to $SSH_HOST:$SSH_PORT:${SCP_DEST}" && \
scp -P ${SSH_PORT} -r ./* ${SSH_USER}@${SSH_HOST}:${SCP_DEST}
