			</div>

			<footer>
				<p>{{Config::get('cms::settings.copyright')}}</p>
			</footer>
		</div>
		{{Notification::show()}}
		{{Asset::container('footer')->scripts()}}
	</body>
</html>