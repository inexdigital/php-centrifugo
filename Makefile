protoc-centrifugo:
	protoc \
		--proto_path=src/proto/centrifugo \
		--php_out=src/ \
		--grpc_out=src/ \
		--plugin=protoc-gen-grpc=grpc_php_plugin \
		api.proto \
	&& mv src/Inexdigital/Centrifugo/Grpc src/ \
	&& rm -rf src/Inexdigital