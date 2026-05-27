#!/bin/bash
# Changing to certificates dir.
cd ./docker/nginx/certs/

# Generating a test self-signed certificate valid for 10 years
openssl req -x509 -nodes -days 3650 -newkey rsa:2048 \
  -keyout auth.key \
  -out auth.crt \
  -subj "/C=US/ST=State/L=City/O=Organization/OU=Department/CN=sh-crm.local"

echo "Test SSL certificates have been successfully created!"