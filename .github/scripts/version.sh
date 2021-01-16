#!/usr/bin/env bash

if test "$GIT_TAG_NAME"; then
  export VERSION=$GIT_TAG_NAME

  # Pass in the tag as the version to write out
  php artisan phpvms:version --write --write-full-version "${VERSION}"
  export FULL_VERSION=$(php artisan phpvms:version)
else
  export BRANCH=${GITHUB_REF##*/}
  echo "On branch $BRANCH"

  # Write the version out but place the branch ID in there
  # This is only for the dev branch
  export BASE_VERSION=$(php artisan phpvms:version --base-only)

  # This now includes the pre-release version, so "-dev" by default
  export VERSION=${BASE_VERSION}

  # Don't pass in a version here, just write out the latest hash
  php artisan phpvms:version --write "${VERSION}"
  export FULL_VERSION=$(php artisan phpvms:version)
fi

export FILE_NAME="phpvms-${VERSION}-test"
export TAR_NAME="$FILE_NAME.tar.gz"
export ZIP_NAME="$FILE_NAME.zip"
export BASE_DIR=`pwd`

echo "::set-env name=FILE_NAME::${FILE_NAME}"
echo "::set-env name=TAR_NAME::${TAR_NAME}"
echo "::set-env name=ZIP_NAME::${ZIP_NAME}"
echo "::set-env name=BASE_DIR::${BASE_DIR}"