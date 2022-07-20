<strong>Install docker and docker-compose</strong>
<ul>
<li>Windows https://store.docker.com/editions/community/docker-ce-desktop-windows/</li>
<li>Linux http://docker.crank.ru/docs/docker-engine/install/on-linux-distributions/</li>
<li>Mac http://docker.crank.ru/docs/docker-engine/install/installation-on-mac-os-x/</li>
</ul>
<strong>Add user to docker group</strong>

This allow you run docker and docker-compose without sudo

<ul>
<li>sudo usermod -aG docker $USER</li>
</ul>

<strong>Easy local setup</strong>
<ul>
  <li>git clone https://github.com/evolv-ai/php-sdk.git</li>
  <li>cd php-sdk </li>
  <li>run docker-compose up -d</li>
  <li>Open http://localhost:8000/</li>
</ul>
